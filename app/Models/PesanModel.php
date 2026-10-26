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
     * Get all conversation partners for a user with last message.
     * Versi kompatibel untuk MySQL 5.7 / MariaDB 10.x (tanpa ROW_NUMBER / CTE).
     */
    public function getConversationList(int $userId)
    {
        $db = \Config\Database::connect();

        // Step 1: Ambil semua partner unik + ID pesan terakhir
        // Menggunakan MAX(id) sebagai penanda "pesan terakhir" (asumsi: id auto increment, jadi lebih besar = lebih baru)
        $sql_partners = "
            SELECT 
                CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS partner_id,
                MAX(id) AS last_msg_id
            FROM pesan
            WHERE sender_id = ? OR receiver_id = ?
            GROUP BY partner_id
            ORDER BY last_msg_id DESC
        ";

        $partners = $db->query($sql_partners, [$userId, $userId, $userId])->getResult();

        if (empty($partners)) {
            return [];
        }

        // Step 2: Ambil detail pesan terakhir, user info, dan unread count per partner
        $result = [];

        foreach ($partners as $p) {
            $partnerId = (int) $p->partner_id;
            $lastMsgId = (int) $p->last_msg_id;

            // Ambil pesan terakhir
            $lastMsg = $db->table('pesan')
                ->select('pesan, created_at')
                ->where('id', $lastMsgId)
                ->get()
                ->getRow();

            // Ambil info partner (user + siswa/pembimbing/instansi)
            $user = $db->table('users')
                ->select('users.id as partner_id, users.username as partner_username,
                          COALESCE(s.nama, p.nama, i.nama_instansi) as partner_nama,
                          COALESCE(s.foto, p.foto) as partner_foto,
                          (SELECT GROUP_CONCAT(ag.name ORDER BY ag.id) 
                           FROM auth_groups_users agu2 
                           JOIN auth_groups ag ON ag.id = agu2.group_id
                           WHERE agu2.user_id = users.id) as partner_role')
                ->join('siswa s', 's.user_id = users.id', 'left')
                ->join('pembimbing p', 'p.user_id = users.id', 'left')
                ->join('instansi i', 'i.user_id = users.id', 'left')
                ->where('users.id', $partnerId)
                ->get()
                ->getRow();

            if (!$user) continue;

            // Hitung unread
            $unread = $db->table('pesan')
                ->where('receiver_id', $userId)
                ->where('sender_id', $partnerId)
                ->where('is_read', 0)
                ->countAllResults();

            $result[] = (object) [
                'partner_id'       => $partnerId,
                'partner_username' => $user->partner_username,
                'partner_nama'     => $user->partner_nama,
                'partner_foto'     => $user->partner_foto,
                'partner_role'     => $user->partner_role,
                'last_message'     => $lastMsg ? $lastMsg->pesan : null,
                'last_time'        => $lastMsg ? $lastMsg->created_at : null,
                'unread_count'     => $unread,
            ];
        }

        return $result;
    }

    /**
     * Count total unread messages for a user
     */
    public function countUnread(int $userId): int
    {
        return $this->db->table($this->table)
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