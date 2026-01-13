<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin Utama (dipertahankan dari seeder asli)
        User::create([
            'nip' => 'admin',
            'name' => 'Administrator Utama',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => null,
            'jabatan' => null,
            'role' => 'admin'
        ]);

        // Data dari Excel, semua sebagai role 'petugas' tanpa terkecuali
        User::create([
            'nip' => '199412192024212011',
            'name' => 'Diah Suwanti, S.T.P',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198404292008122001',
            'name' => 'Ima Diana Sari, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198403102009122005',
            'name' => 'Dian Cahyaningsih, S.Farm,Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199504252019032004',
            'name' => 'Annisa Aprilia Wahyu Dhinari, S.H',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197907102006042004',
            'name' => 'Purwaningdyah Reni Hapsari, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199308242019032003',
            'name' => 'Syafiana Khusna Aini, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198001062005012003',
            'name' => 'Dindar Dianarum Ekowulan, SF, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198512172008122001',
            'name' => 'Ida Rosiana, S.TP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199306062025212017',
            'name' => 'Novita Kornelia, A.Md.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'VII',
            'jabatan' => 'Pengelola Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197306132000032001',
            'name' => 'Juni Ratnawati, S.Si, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196902121992032001',
            'name' => 'Elti Haningsih, SKM',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198512022008122002',
            'name' => 'Desy Anindyasari, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pranata Keuangan APBN Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198606242010122002',
            'name' => 'Hanum Permana Putri, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197203211998032002',
            'name' => 'Dwi Ernawati, S.Si, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina Tk. I / IV/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197106231999032001',
            'name' => 'Sri Hartati, S.Si, Apt.,M.Sc',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197905292005012001',
            'name' => 'Yuli Hartanti, S.Si, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197009251990032001',
            'name' => 'Arimurti Mugiarsih',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Penyelia',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198004142005012001',
            'name' => 'Sulistyaningsih, ST',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198211022006042002',
            'name' => 'Sukma Radyaswati, S.TP,. M.Sc',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198212042008122001',
            'name' => 'Hayyu Rini Citraningtyas, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196812251993032001',
            'name' => 'Mulyaningsih, SKM',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198106172006042004',
            'name' => 'Diah Kusumaningrum, S.T.P.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198012112000032001',
            'name' => 'Chusnul Malik Hanifah, S.TP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197706042002122009',
            'name' => 'Yuyun Wijayanti, S.Si,Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198011282006042020',
            'name' => 'Eny Suryani, S.Far, Apt, M.M.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198004132006042004',
            'name' => 'Slamet Lestari, S.TP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199003102012122001',
            'name' => 'Firdha Kurniawati, S.T.P.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199009082014022001',
            'name' => 'Setyowati, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198703192015021002',
            'name' => 'Adhe Sri Marjuki, S.Farm, Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199511212020121001',
            'name' => 'Hilmam Aditama, S.T.P.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198811152015021001',
            'name' => 'Taufan Adi Wibowo, SH',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199609192025211013',
            'name' => 'Rizky Ary Wardhana, S.Kom',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Pranata Komputer Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198012232006042003',
            'name' => 'Tri Nurkhayati, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198408122006042002',
            'name' => 'Ratna Porwanti, S.T.P.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '200211282025101001',
            'name' => 'Syahril Ramadan, S.In.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198701042009121002',
            'name' => 'Tirta Setya Bhakti, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198701302010121005',
            'name' => 'A Rizal Permana, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198103132006042003',
            'name' => 'Ika Dian Wahyuni, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199008142025211013',
            'name' => 'Dena Adi Santosa, S.I.Kom.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Penata Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199102142019032003',
            'name' => 'Elok Atiqoh, S.Farm., Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198101102005011001',
            'name' => 'Yohanes Bosco Ari Cahyo Hartono, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198010282005012001',
            'name' => 'Pniel Mardiana Chandra, S.Si, Apt,. M.A.B',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199308122019032005',
            'name' => 'Fahrun Nisa, S.H',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198310052008122001',
            'name' => 'Erna Kurniawati, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198704012010121004',
            'name' => 'Catur Kurniaji, S.Farm.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198308292008121001',
            'name' => 'Risad Setiadi, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199402032020122002',
            'name' => 'Tifa Dwaya Merdeka Putri, S.E',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Analis SDM Aparatur Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199506162019032006',
            'name' => 'Arini Dyah Sri Puspita Dewi, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198508212007122001',
            'name' => 'Maria Regina Arwindya Retni Andrasita, S.Sos',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198603032010122004',
            'name' => 'Shinta Dewi Akhirnawati, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198702042010122002',
            'name' => 'Lucy Rahmadesi, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199408202025212024',
            'name' => 'Maulina Rahmana Waskito, S.TP.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198312232010122003',
            'name' => 'Laksmi Ardhya Sari, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198002262006042002',
            'name' => 'Nur Faridah Amin, S.Si, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197201301997032001',
            'name' => 'Nur Afifah, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Penyelia',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196609201995031001',
            'name' => 'Drs. Matheus Kristianto, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pelaksana',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197911102006042003',
            'name' => 'Nur Rahmawati, S.Si, Apt., M.H.Kes.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198412302010121004',
            'name' => 'Sigit Hartomo, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198206012006042002',
            'name' => 'Elisa Kesumaesthy, S.Farm., Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196901161990032001',
            'name' => 'Theresiana Ari Wijayanti, SH',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina Tk. I / IV/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197901172003121001',
            'name' => 'Mustofa, SF, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198305202007122002',
            'name' => 'Maya Yuvita Mappapa, ST',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198108252005012002',
            'name' => 'Suci Wulandari, STP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198608112012122001',
            'name' => 'Putri Nur Setyani, S.Far, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198604132010122003',
            'name' => 'Sila Fiqhi Dauati, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198503132008122002',
            'name' => 'Purnaning, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196805171995032001',
            'name' => 'Dra. Aryanti, Apt, MSi.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina Utama Muda / IV/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199606122019032004',
            'name' => 'Dwi Rahmadita, S.H',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198608102010122006',
            'name' => 'Agustina Wulandari, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197111081994032001',
            'name' => 'Nency Kristanti, S.TP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197808062005012001',
            'name' => 'Arini Himawati, SF, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197907152006042001',
            'name' => 'Rini Hidayati, S.F, Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198602262009121002',
            'name' => 'Firman Erry Probo, S.Far, Apt, M.H.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197107091995031001',
            'name' => 'Sumito, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199002272025212026',
            'name' => 'Respati Arsenda, A.Md.Kom',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'VII',
            'jabatan' => 'Pengelola Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197805172006042002',
            'name' => 'Nur Cahyaningsih, S.T.P.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197811102006042003',
            'name' => 'Novian Damayanti, S.TP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198204142006042003',
            'name' => 'Nimas Putri Handayani, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196609091993032002',
            'name' => 'Dra. Rustyawati, Apt, M.Kes.(Epid)',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IV/d',
            'jabatan' => 'Kepala Balai Besar POM di Semarang',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '200207042025101001',
            'name' => 'Wahyu Dharma Hadi Saputra, S.Si.In.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197202011992032001',
            'name' => 'Eka Untari, SKM',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198511032010122003',
            'name' => 'Dyah Ayu Novi Hapsari, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Penata Kelola Obat dan Makanan',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198106262005012001',
            'name' => 'Fina Triyanti Hari Utami, S.Farm',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198209252007121001',
            'name' => 'Anjar Putro Pribadi, S.H.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199301212019032004',
            'name' => 'Nindi Wulandari, S.Farm.,Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199111102025211031',
            'name' => 'Herdi Pradesa, S.T.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Penata Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198103242005012002',
            'name' => 'Astiti Kusmaningrum, STP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198507212019032005',
            'name' => 'Setya Wulan Widaningsih, S.Farm., Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196812241997032002',
            'name' => 'Dra. Daniel Kristini, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina Utama Muda / IV/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196805061991032002',
            'name' => 'Margiyani, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Asisten Kelola Obat dan Makanan',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198002152006042004',
            'name' => 'Suhriyah, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198101252006041004',
            'name' => 'Ronald Hatoguan Manik, STP, MBA',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198504232025211054',
            'name' => 'Fulal Rachmat Tsalasa',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => 'Operator Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199210192015022001',
            'name' => 'Farida Nur Malika, S.Si.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198112192005012001',
            'name' => 'Kurniasanti, S.Farm, Apt., M.H.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198506282007122001',
            'name' => 'Noni Devi Arifa, S. Farm',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198710042010122001',
            'name' => 'Retno Hari Wahyuni, S.Farm, Apt., M.T.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198504172007122001',
            'name' => 'Faridha Maera Lokana, S.Farm.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196806051999032001',
            'name' => 'Dra. Eni Zuniati, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196909011992032001',
            'name' => 'Ratna Sri Hidayati, SKM',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197312011996032001',
            'name' => 'Tri Wahyu Pujiasih, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Penyelia',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198406032008122001',
            'name' => 'Nahriyati, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198702282009122001',
            'name' => 'Esther Sibarani, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198203302006042004',
            'name' => 'Indah Rizki Fitriani, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198005052005012002',
            'name' => 'Yudyaswari Peni Hapsari, S.Si, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198006122006042005',
            'name' => 'Filia Candra Yunita, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196909271991031001',
            'name' => 'Teguh Budiono',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Penyelia',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198205022006042008',
            'name' => 'Meilani Dwi Lestari, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198206162006042002',
            'name' => 'Kurnia Dwi Widyarini, S.Farm, Apt.,M.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199102202019032005',
            'name' => 'Ria Endriyani, S.TP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197504261994032001',
            'name' => 'Retno Purwaningsih, SKM',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198105292005012002',
            'name' => 'Zakiah Kurniati, S.Farm, Apt, M.Sc',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina Tk. I / IV/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199209272015022003',
            'name' => 'Dinda Choiria Noor, S.Si.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198210132007122001',
            'name' => 'Linda Prasetyawati, S.Farm, Apt.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Penata Kelola Obat dan Makanan',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '196909131992032001',
            'name' => 'Sri Lestari',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Asisten Kelola Obat dan Makanan',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199612132019031002',
            'name' => 'Ahmad Al Arif, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198109062007121001',
            'name' => 'Etta Hermawan, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Asisten Kelola Obat dan Makanan',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198404162010122004',
            'name' => 'Savitri Neniasti Prabhata, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199312242025212025',
            'name' => 'Desiana Dwi Astuti, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'VII',
            'jabatan' => 'Pengelola Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199608302019031001',
            'name' => 'Naufal Haryoseto Zahiruddin, SH',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198501142025211020',
            'name' => 'Firdaus Slamet Widodo, S.Kom',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Penata Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197910082006042001',
            'name' => 'Okti Puji Astuti, S.Si, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199512052022032003',
            'name' => 'Indah Muji Mulyani, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198706282010122007',
            'name' => 'Putik Pribadi, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197904102005012001',
            'name' => 'Diana Silawati, SF, Apt.,M.Sc',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Madya',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199503192019031004',
            'name' => 'Jihad Afghan Garuda Mataram, SH',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199003032012122001',
            'name' => 'Rita Riata, S.Farm, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198408142007122001',
            'name' => 'Dhevi Setia Puspita, S.Farm',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197303181997032001',
            'name' => 'Retno Warsiningsih, SKM',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198606022009122006',
            'name' => 'Tristya Yunita, STP',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198207242005012001',
            'name' => 'Shinta Pratiwi, S.Kom',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Penata Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199011182014022005',
            'name' => 'Dhiah Fatma Sari, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pranata Keuangan APBN Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198808072014022004',
            'name' => 'Diksi Sandra Agustin, SE',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Perencana Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198308192010121001',
            'name' => 'Agung Wijayanto, S.Kom',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Penata Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198011302006042005',
            'name' => 'Dewi Marya Achmad, S.Far, Apt',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Pembina / IV/a',
            'jabatan' => 'Kepala Bagian Tata Usaha pada Balai Besar POM di Semarang',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199501032019031003',
            'name' => 'Chandra Dwi Setyawan, SE',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Penata Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199011262019032004',
            'name' => 'Dwi Kustiani, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'II/d',
            'jabatan' => 'Penata Laksana Barang Terampil',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197202071997032001',
            'name' => 'Jety Suprobo Eny, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Arsiparis Penyelia',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197101301992032001',
            'name' => 'Tumiyarsih, SE',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Analis Pengelolaan Keuangan APBN Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197511222014061001',
            'name' => 'Kustiyono',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'II/c',
            'jabatan' => 'Operator Layanan Operasional',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199712142022032004',
            'name' => 'Windi Putri Nur Aini, S.T.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pranata Komputer Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198406012009122002',
            'name' => 'Dian Normawati, S.Si',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Arsiparis Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199009212025212012',
            'name' => 'Septa Yenita, S.E.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'IX',
            'jabatan' => 'Arsiparis Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197606171997032001',
            'name' => 'Elizabeth Yunita Hernawati, S.Pd',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Tk. I / III/d',
            'jabatan' => 'Analis SDM Aparatur Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199106222015022003',
            'name' => 'Bias Tiaradini, S.Ak.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Penata Laksana Barang Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198704112008121001',
            'name' => 'Moh Lukman Firmansyah, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pranata Keuangan APBN Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197708212008121001',
            'name' => 'Nurkholis, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pranata Keuangan APBN Penyelia',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '197012262000032001',
            'name' => 'Tri Pujiati, S.Kom',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Pranata Komputer Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198107172005012001',
            'name' => 'Endah Sri Wahyuni, S.E.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda Tk. I / III/b',
            'jabatan' => 'Perencana Ahli Pertama',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198206162005012001',
            'name' => 'Dian Wahyu Purwaningtyas, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Arsiparis Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '199104232015022006',
            'name' => 'Sheila Aprilia Putri, A.Md',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata Muda / III/a',
            'jabatan' => 'Pranata SDM Aparatur Mahir',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '198606182009121001',
            'name' => 'Sahat Nicolus Wicaksono Panggabean, S.E., M.M.',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => 'Penata / III/c',
            'jabatan' => 'Analis SDM Aparatur Ahli Muda',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '101',
            'name' => 'Agung Tri Prasetyo',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '102',
            'name' => 'Gita',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '103',
            'name' => 'Achmad Syafiq',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '104',
            'name' => 'Ery pitono',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '105',
            'name' => 'Endhi Wartono Satmoko',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '106',
            'name' => 'Tutur Tri Misyuwur',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '107',
            'name' => 'Triyo Gunawan',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '108',
            'name' => 'Edi Purnomo',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '109',
            'name' => 'Mukh Yasfin Rizaldi',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '110',
            'name' => 'Agan Waskito',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '111',
            'name' => 'Bambang Widodo',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '112',
            'name' => 'Ari Christiyanto',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '113',
            'name' => 'Ramelan',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '114',
            'name' => 'Kiki Mardianto',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '115',
            'name' => 'Farid  Ma\'ruf',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '116',
            'name' => 'Kukuh Maulana',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '117',
            'name' => 'R. Premono Adikusumo',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '118',
            'name' => 'Choirul Umam Hasan',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '119',
            'name' => 'Solechan',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '120',
            'name' => 'Dany Listanto',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '121',
            'name' => 'Heru Gunawan',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '122',
            'name' => 'Suhardo',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '123',
            'name' => 'Yahudi',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '124',
            'name' => 'Sholeh Alqaffi',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '125',
            'name' => 'Daru Putra Pratama',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => '126',
            'name' => 'Hendra Gunawan',
            'password' => bcrypt('tikbpomsmg'),
            'pangkat' => '-',
            'jabatan' => '-',
            'role' => 'petugas'
        ]);
    }
}