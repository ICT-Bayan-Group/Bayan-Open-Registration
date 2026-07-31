<?php

/**
 * Data statis Hotel & Paket Tour — Bayan Open 2026
 * Sesuai PRD "Akomodasi & Tour" §7.3 & §8
 *
 * Tidak ada database/Filament di v1 — file ini adalah satu-satunya sumber data,
 * dipakai baik di section preview homepage maupun halaman penuh /akomodasi-tour.
 *
 * image_url sengaja dikosongkan (null) untuk sebagian besar hotel — biarkan
 * fallback ilustrasi/icon per tier yang jalan (lihat akomodasi-tour.blade.php
 * & partials/homepage-akomodasi-section.blade.php). Kalau nanti ada foto asli,
 * upload ke Cloudinary lalu isi URL-nya di field ini.
 *
 * Semua URL Cloudinary sudah disisipi transformasi f_auto,q_auto,w_600,c_fill
 * supaya format & kualitas gambar otomatis dioptimasi (WebP/AVIF bila
 * didukung browser) dan ukuran file jauh lebih kecil -> loading lebih cepat.
 *
 * Update terakhir data: 31 Juli 2026 (dari brosur cetak PBSI & partner tour).
 */

return [

    'updated_at' => '31 Juli 2026',

    'hotels' => [
// ✅ BENAR
        ['name' => 'Platinum Hotel & Convention Hall Balikpapan', 'room_type' => 'Deluxe', 'rate' => 828000, 'tier' => 'premium', 'is_official' => true, 'image_url' => 'https://res.cloudinary.com/djs5pi7ev/image/upload/f_auto,q_auto,w_600,c_fill/v1785480156/504660739_nmnshi.jpg'],  
        ['name' => 'Golden Tulip Balikpapan',                     'room_type' => 'Deluxe',          'rate' => 800000,  'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483157/89732501_XL_iuvkdc.jpg'],
        ['name' => 'Swissbel Hotel Balikpapan',                   'room_type' => 'Deluxe',          'rate' => 650000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483157/swiss-belhotel-balikpapan-facade-3-1920w_xv57hg.webp'],
        ['name' => 'Novotel Balikpapan',                          'room_type' => 'Superior',        'rate' => 950000,  'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483157/expedia_group-167742-196789730-524170_iakv2c.jpg'],
        ['name' => 'Bluesky Hotel',                                'room_type' => 'Business',        'rate' => 700000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483157/463290917_x9dsj3.jpg'],
        ['name' => 'Grand Jatra',                                  'room_type' => 'Superior',        'rate' => 1000000, 'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483156/694486756_ahdqvz.jpg'],
        ['name' => 'Astara',                                       'room_type' => 'Superior',        'rate' => 800000,  'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483156/12d26f7c_a7mglo.webp'],
        ['name' => 'Pentacity Hotel',                              'room_type' => 'Superior',        'rate' => 900000,  'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483156/419525932_d4wijy.jpg'],
        ['name' => 'Grand Tjokro',                                 'room_type' => 'Superior',        'rate' => 771000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483155/255503095_wfvjcj.jpg'],
        ['name' => 'Four Points Hotel',                            'room_type' => 'Deluxe',          'rate' => 1100000, 'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483156/exterior_yq52tl.jpg'],
        ['name' => 'Gran Senyiur',                                 'room_type' => 'Superior',        'rate' => 800000,  'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483163/i000001bf1ab9396980884595059a859a0afe6e_large_jm2bjh.jpg'],
        ['name' => 'Horison Ultima Bandara',                       'room_type' => 'Deluxe',          'rate' => 700000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483155/5556f65b-92e7-42af-8367-535ec40f3d41-1687487440622-7a6920f17fa7a2fc7b74d208166bdf09_qhyrlq.webp'],
        ['name' => 'Grand Tiga Mustika',                           'room_type' => 'Superior',        'rate' => 500000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483154/65b8bab4-8020-4d3f-afb7-a3891bcc4d89-1603921133282-43e26200288e080ecec682b56e2e939b_kpyvoa.webp'],
        ['name' => 'Pacific Hotel',                                'room_type' => 'Superior',        'rate' => 488000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483155/2677309397_WxH_yk8ywh.jpg'],
        ['name' => 'Zurich Hotel',                                 'room_type' => 'Standard',        'rate' => 475000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483156/39176418_onazcw.jpg'],
        ['name' => 'Horison Sagita Balikpapan',                    'room_type' => 'Business',        'rate' => 550000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483154/hotel-sagita-balikpapan_p61g1t.jpg'],
        ['name' => 'Ibis Hotel Balikpapan',                        'room_type' => 'Standard',        'rate' => 550000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483153/7443_ho_00_p_1024x768_ucgufs.jpg'],
        ['name' => 'Quest Hotel Balikpapan',                       'room_type' => 'Superior',        'rate' => 605000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483153/929108_15062316090030485792_vrb6t8.jpg'],
        ['name' => 'BDI Town House',                               'room_type' => 'Superior',        'rate' => 478000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483154/20025876-4b44e4ad1c7c768d0724bb1f0287cb7e_ablchb.webp'],
        ['name' => 'NEO+',                                         'room_type' => 'Superior',        'rate' => 525000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/v1785483153/Hotel_Neo_Balikpapan.1535358146.471.ori_gfskjw.jpg'],
        ['name' => 'Swissbel Inn',                                 'room_type' => 'Deluxe',          'rate' => 571000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483154/DJI_0296-1920w_mbzfy5.webp'],
        ['name' => 'Maxone Hotel',                                 'room_type' => 'Happiness',       'rate' => 658000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483153/maxone_caqnpv.jpg'],
        ["name" => "D'Prima",                                      'room_type' => 'Superior',        'rate' => 500000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483153/20045146-453a8dc72b1b290c68290d045708f9b7_gwki1f.webp'],
        ['name' => 'Whiz Hotel',                                   'room_type' => 'Superior',        'rate' => 525000,  'tier' => 'standard', 'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483151/SnapInsta.to_15802031_230529104070041_8263582489855918080_n_bq1fjn.webp'],
        ['name' => 'Midtown Xpress',                               'room_type' => 'Cool Room',       'rate' => 457600,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483151/midtown-xpress-balikpapan_ucbjao.jpg'],
        ['name' => 'Mega Lestari Hotel',                           'room_type' => 'Superior',        'rate' => 450000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483151/ID.BPP.HT.MEGALESTARI_1_xejnep.jpg'],
        ['name' => 'The Point Hotel',                              'room_type' => 'Standard',        'rate' => 400000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483152/20070373-9aac400826f760561ff5cf84b182d635_wigyl3.webp'],
        ['name' => 'PILLOW',                                       'room_type' => 'Superior',        'rate' => 290000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483152/34416f008b1354ba85f1f5805bc989d1_rcycyf.jpg'],
        ['name' => 'La Casa Borneo',                               'room_type' => 'Cabana',          'rate' => 1900000, 'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785485533/828775632b233168d18f8fb9849438ff_qvrl9u.jpg'],
        ['name' => 'Sepinggan',                                    'room_type' => 'Deluxe',          'rate' => 385000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483152/3de6376edc18ed50b149ff9e52d1c907_jvrmba.jpg'],
        ['name' => 'The Hill Residence',                           'room_type' => 'Executive House',  'rate' => 2500000, 'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483153/a8429b62-6af3-4a65-87f7-255e2045baac-1663834531435-a3447c41633979a795ef11a9672dc1a1_verpbo.webp'],
        ['name' => 'Mahligai Beach Resort',                        'room_type' => 'Manggar Villa',   'rate' => 1000000, 'tier' => 'premium',  'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483152/24fb0dce-f53c-4e3b-acbe-8d90147e32e1-1726130726970-78811c921fa30f3909799f601d74f40a_oxpuxz.webp'],
        ['name' => 'BEST IN',                                      'room_type' => 'Superior',        'rate' => 380000,  'tier' => 'budget',   'is_official' => false, 'image_url' => 'https://res.cloudinary.com/viecqvpk/image/upload/f_auto,q_auto,w_600,c_fill/v1785483151/c8ed8db8b5e6abf07890b39ba79ff045_im6y66.jpg'],
    ],

    'tours' => [
        [
            'slug'          => 'city-tour-4-jam',
            'title'         => 'Balikpapan City Tour 4 Jam',
            'duration'      => '4 Jam',
            'description'   => 'Mulai dari Pasar Kebun Sayur — surganya oleh-oleh khas Kalimantan seperti batu mulia, manik-manik, kaos khas, kerajinan kayu Bajaka, sampai camilan amplang. Lanjut keliling kawasan Kilang Minyak Balikpapan, lewat Pelabuhan Semayang, dan ditutup dengan foto-foto cantik di pantai pasir putih Kilang Mandiri saat matahari terbenam.',
            'highlights'    => [
                'Belanja oleh-oleh khas Kalimantan',
                'Keliling Kilang Minyak & Pelabuhan Semayang',
                'Sunset di Pantai Kilang Mandiri',
            ],
            'includes'      => ['Mobil', 'Driver sekaligus guide', 'Tiket masuk pantai'],
            'price'         => 150000,
            'min_person'    => 5,
            'contact_phone' => '62811544453',
            'icon'          => 'market',
            'image_url'     => null,
        ],
        [
            'slug'          => 'buaya-kebun-sayur-pantai-6-jam',
            'title'         => 'Balikpapan City Tour, Buaya, Kebun Sayur & Pantai 6 Jam',
            'duration'      => '6 Jam',
            'description'   => 'Berangkat menuju penangkaran buaya terbesar di Kalimantan — kamu bisa lihat atraksi pemberian makan, foto bareng buaya, bahkan pegang anak buaya langsung, plus koleksi lengkap buaya muara, air tawar, hingga buaya supit. Ada juga kesempatan naik gajah. Setelahnya lanjut belanja di Pasar Kebun Sayur, keliling Kilang Minyak, dan ditutup sunset di pantai.',
            'highlights'    => [
                'Atraksi & foto bareng buaya',
                'Naik gajah',
                'Belanja oleh-oleh + sunset pantai',
            ],
            'includes'      => ['Mobil', 'Driver sekaligus guide', 'Tiket masuk lokasi'],
            'price'         => 200000,
            'min_person'    => 5,
            'contact_phone' => '62811544453',
            'icon'          => 'crocodile',
            'image_url'     => null,
        ],
        [
            'slug'          => 'tour-ikn-8-jam',
            'title'         => 'Tour ke IKN 8 Jam',
            'duration'      => '8 Jam',
            'description'   => 'Lihat langsung wajah Ibu Kota Nusantara. Perjalanan sekitar 2 jam via tol melewati Bukit Suharto — jangan kaget kalau papasan sama monyet khas Kalimantan di jalan. Sesampainya di IKN, kamu akan diajak naik mobil listrik berkeliling Istana Negara, Plaza Ceremoni, dan Sumbu Kebangsaan, sambil menikmati pemandangan istana negara dari titik tertinggi. Kalau waktu memungkinkan, mampir sebentar ke Pusat Rehabilitasi Beruang Madu.',
            'highlights'    => [
                'Keliling IKN naik mobil listrik',
                'Istana Negara & Sumbu Kebangsaan',
                'Opsional: Pusat Rehabilitasi Beruang Madu',
            ],
            'includes'      => ['Tol', 'Tiket masuk beruang madu', 'Kendaraan'],
            'price'         => 300000,
            'min_person'    => 5,
            'contact_phone' => '62811544453',
            'icon'          => 'ikn',
            'image_url'     => null,
        ],
    ],

];