const fs = require('fs');
let c = fs.readFileSync('app/Views/main.php', 'utf8');

const cssOpt = `
    <!-- Performance & Scroll Optimizations -->
    <style>
        /* Menggunakan GPU acceleration untuk animasi dan scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        .main-content, .sidenav {
            -webkit-overflow-scrolling: touch; /* untuk iOS */
            will-change: transform, scroll-position;
        }

        /* Mengurangi beban render pada elemen kaca (glassmorphism) yang berat saat scroll */
        .navbar-blur {
            backdrop-filter: blur(5px) saturate(150%) !important;
            -webkit-backdrop-filter: blur(5px) saturate(150%) !important;
        }

        /* Mencegah lag akibat box-shadow pada kartu besar */
        .card {
            will-change: transform;
            transform: translateZ(0); /* Force Hardware Acceleration */
        }

        /* Optimasi smooth-scrollbar jika diinisialisasi */
        [data-scrollbar] {
            overscroll-behavior: contain;
        }
    </style>
</head>`;

if (!c.includes('<!-- Performance & Scroll Optimizations -->')) {
    c = c.replace('</head>', cssOpt);
    fs.writeFileSync('app/Views/main.php', c);
    console.log("Injected CSS optimizations");
} else {
    console.log("Already optimized");
}
