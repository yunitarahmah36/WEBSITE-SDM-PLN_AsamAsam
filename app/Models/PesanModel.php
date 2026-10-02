<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pesan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sender_id', 'receiver_id', 'pesan', 'is_read'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get conversation between two users
     */
    public function getConversation(int $userA, int $userB)
    {
        return $this->db->table('pesan')
            ->select('pesan.*, 
                su.username as sender_username,
                ru.username as receiver_username')
            ->join('users su', 'su.id = pesan.sender_id', 'left')
            ->join('users ru', 'ru.id = pesan.receiver_id', 'left')
            ->groupStart()
                ->where('pesan.sender_id', $userA)
                ->where('pesan.receiver_id', $userB)
            ->groupEnd()
            ->orGroupStart()
                ->where('pesan.sender_id', $userB)
                ->where('pesan.receiver_id', $userA)
            ->groupEnd()
            ->orderBy('pesan.created_at', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Get all conversation partners for a user with last message
     */
    public function getConversationList(int $userId)
    {
        $db = \Config\Database::connect();
        $sql = "
            SELECT 
                u.id as partner_id,
                u.username as partner_username,
                COALESCE(s.nama, p.nama, d.nama) as partner_nama,
                COALESCE(s.foto, p.foto, d.foto) as partner_foto,
                (SELECT GROUP_CONCAT(ag.name ORDER BY ag.id) 
                 FROM auth_groups_users agu2 
                 JOIN auth_groups ag ON ag.id = agu2.group_id
                 WHERE agu2.user_id = u.id) as partner_role,
                last_msg.pesan as last_message,
                last_msg.created_at as last_time,
                (SELECT COUNT(*) FROM pesan WHERE receiver_id = ? AND sender_id = u.id AND is_read = 0) as unread_count
            FROM users u
            LEFT JOIN siswa s ON s.user_id = u.id
            LEFT JOIN pembimbing p ON p.user_id = u.id
            LEFT JOIN dosen_pembimbing d ON d.user_id = u.id
            JOIN (
                SELECT 
                    CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as partner_id,
                    pesan,
                    created_at,
                    ROW_NUMBER() OVER (PARTITION BY CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END ORDER BY created_at DESC) as rn
                FROM pesan
                WHERE sender_id = ? OR receiver_id = ?
            ) last_msg ON last_msg.partner_id = u.id AND last_msg.rn = 1
            ORDER BY last_msg.created_at DESC
        ";
        return $db->query($sql, [$userId, $userId, $userId, $userId, $userId])->getResult();
    }

    /**
     * Count total unread messages for a user
     */
    public function countUnread(int $userId): int
    {
        return $this->db->table($this->table)
            ->join('users', 'users.id = pesan.sender_id')
            ->where('pesan.receiver_id', $userId)
            ->where('pesan.is_read', 0)
            ->countAllResults();
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(int $senderId, int $receiverId): void
    {
        $this->db->table($this->table)
             ->where('sender_id', $senderId)
             ->where('receiver_id', $receiverId)
             ->update(['is_read' => 1]);
    }
}
