<?php
// Variabel status permainan
$pertanyaanSekarang = [];
$skor = 0;
$waktuTersisa = 0;
$sedangBermain = false;
$jumlahSoal = 0;
$maksimalSoal = 0;
$waktuPerSoal = 0;

// Fungsi untuk menghasilkan angka acak berdasarkan tingkat kesulitan
function hasilkanAngka($tingkatKesulitan) {
    switch ($tingkatKesulitan) {
        case 1: // Mudah: angka 1-10
            $maksimal = 10;
            break;
        case 2: // Sedang: angka 1-50
            $maksimal = 50;
            break;
        case 3: // Sulit: angka 1-100
            $maksimal = 100;
            break;
        default:
            $maksimal = 10;
    }
    return rand(1, $maksimal);
}

// Fungsi untuk menghasilkan soal pembagian dengan hasil bulat
function hasilkanSoalPembagian($tingkatKesulitan) {
    $maksimal = $tingkatKesulitan == 1 ? 10 : ($tingkatKesulitan == 2 ? 50 : 100);

    $pembagi = rand(1, min(10, $maksimal));
    $hasil = rand(1, floor($maksimal / $pembagi));
    $yangDibagi = $hasil * $pembagi;

    return [
        'angka1' => $yangDibagi,
        'angka2' => $pembagi,
        'hasil' => $hasil
    ];
}

// Fungsi untuk menghasilkan pertanyaan baru
function hasilkanPertanyaan($tingkatKesulitan) {
    global $jumlahSoal, $maksimalSoal, $pertanyaanSekarang;

    $jumlahSoal++;

    if ($jumlahSoal > $maksimalSoal) {
        return "Permainan selesai!";
    }

    $operasi = $tingkatKesulitan == 1 ? ['+', '-'] : ['+', '-', '*', '/'];
    $operasiTerpilih = $operasi[array_rand($operasi)];

    if ($operasiTerpilih == '/') {
        $soalPembagian = hasilkanSoalPembagian($tingkatKesulitan);
        $angka1 = $soalPembagian['angka1'];
        $angka2 = $soalPembagian['angka2'];
        $jawaban = $soalPembagian['hasil'];
    } else {
        $angka1 = hasilkanAngka($tingkatKesulitan);
        $angka2 = hasilkanAngka($tingkatKesulitan);

        switch ($operasiTerpilih) {
            case '+':
                $jawaban = $angka1 + $angka2;
                break;
            case '-':
                if ($angka1 < $angka2) {
                    list($angka1, $angka2) = [$angka2, $angka1];
                }
                $jawaban = $angka1 - $angka2;
                break;
            case '*':
                $jawaban = $angka1 * $angka2;
                break;
        }
    }

    $pertanyaanSekarang = [
        'pertanyaan' => "$angka1 $operasiTerpilih $angka2 = ?",
        'jawaban' => $jawaban
    ];

    return $pertanyaanSekarang;
}

// Fungsi untuk memulai permainan
function mulaiPermainan($tingkatKesulitan) {
    global $maksimalSoal, $waktuPerSoal, $sedangBermain, $jumlahSoal, $skor;

    switch ($tingkatKesulitan) {
        case 1: // Mudah
            $maksimalSoal = 5;
            $waktuPerSoal = 10;
            break;
        case 2: // Sedang
            $maksimalSoal = 7;
            $waktuPerSoal = 7;
            break;
        case 3: // Sulit
            $maksimalSoal = 10;
            $waktuPerSoal = 5;
            break;
    }

    $sedangBermain = true;
    $skor = 0;
    $jumlahSoal = 0;

    return hasilkanPertanyaan($tingkatKesulitan);
}

// Fungsi untuk memeriksa jawaban
function periksaJawaban($jawabanPengguna) {
    global $pertanyaanSekarang, $skor;

    if ($jawabanPengguna == $pertanyaanSekarang['jawaban']) {
        $skor++;
        return "Benar!";
    } else {
        return "Salah! Jawaban yang benar adalah " . $pertanyaanSekarang['jawaban'];
    }
}

// Contoh pemanggilan
$tingkatKesulitan = 2; // Sedang
$soal = mulaiPermainan($tingkatKesulitan);
print_r($soal); // Tampilkan soal pertama
echo periksaJawaban(12); // Contoh jawaban pengguna
