<?php
// var_dump(PHP_OS);
// die;
$file	= '../db/database.json';
if (!file_exists($file)) {
	echo "<h1>Jalankan admin terlebih dahulu</h1>";
	die;
}
$json 	= file_get_contents($file);
$db		= json_decode($json, true);
$showDb	= $db;
unset($showDb['akses']);

$info_timer			= $db['timer']['info'] 		* 1000;	//detik
$wallpaper_timer	= $db['timer']['wallpaper'] * 1000;
$adzan_timer		= $db['timer']['adzan'] 	* 1000 * 60; //menit
// $iqomah_timer		= $db['timer']['iqomah'] 	* 1000 * 60;
$sholat_timer		= $db['timer']['sholat'] 	* 1000 * 60;

//optional
$khutbah_jumat		= $db['jumat']['duration'] 	* 1000 * 60;
$sholat_tarawih		= $db['tarawih']['duration'] 	* 1000 * 60;

//Logo
// nge trik ==> kalo replace file, di display logo yang lama masih kesimpen di cache ==> solusi ganti logo ganti nama file 
$dirLogo	= 'logo/';
$filesLogo	= array_diff(scandir($dirLogo), array('.', '..', 'Thumbs.db'));
$filesLogo	= array_values($filesLogo); //re index
$logo		= $filesLogo[0];


$dir	= 'wallpaper/';
$files	= array_diff(scandir($dir), array('.', '..', 'Thumbs.db'));
$wallpaper	= '';
$i	= 0;
foreach ($files as $v) {
	$active	= $i == 0 ? 'active' : '';
	$wallpaper	.= '<div class="item slides ' . $active . '"><div style="background-image: url(wallpaper/' . $v . ');"></div></div>';
	$i++;
}
// print_r($files);die;
?>


<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Display|Masjid</title>
	<link rel="icon" type="image/png" href="../icon.png" />
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<style>
		<?php if(!empty($db['latar']['khutbah'])): ?>
		#display-khutbah { background-image: url('img/<?=$db['latar']['khutbah']?>') !important; background-size: contain !important; background-position: center !important; }
		<?php endif; ?>
		<?php if(!empty($db['latar']['adzan'])): ?>
		#display-adzan { background-image: url('img/<?=$db['latar']['adzan']?>') !important; background-size: contain !important; background-position: center !important; }
		<?php endif; ?>
		<?php if(!empty($db['latar']['iqomah'])): ?>
		#count-down { background-image: url('img/<?=$db['latar']['iqomah']?>') !important; background-size: contain !important; background-position: center !important; }
		<?php endif; ?>
		<?php if(!empty($db['latar']['sholat'])): ?>
		#display-sholat { background-image: url('img/<?=$db['latar']['sholat']?>') !important; background-size: contain !important; background-position: center !important; }
		<?php endif; ?>
	</style>
</head>

<body>
	<div id="preloader">
		<div id="status">&nbsp;</div>
	</div>


	<div id="full-screen-clock" style="display:none"></div>
	<div id="count-down" class="full-screen" style="display:none">
		<div class="counter">
			<h1>COUNTER</h1>
			<div class="hh">00<span>JAM</span></div>
			<div class="ii">00<span>MENIT</span></div>
			<div class="ss">00<span>DETIK</span></div>
		</div>
	</div>
	<div id="display-adzan" class="full-screen" style="display:none">
		<div></div>
	</div>
	<div id="display-sholat" class="full-screen" style="display:none"></div>
	<div class="carousel carousel-fade fade-carousel slide" data-ride="carousel" data-interval="<?= $wallpaper_timer ?>">
		<!-- Overlay -->
		<div class="overlay"></div>
		<!-- Wrapper for slides -->
		<div class="carousel-inner"><?= $wallpaper ?></div>
	</div>

	<div id="left-container">
		<div class="sidebar-header">
			<div class="sidebar-logo" style="background-image: url(logo/<?= $logo ?>);"></div>
		</div>
		<div id="jam"></div>
		<div id="tgl"></div>
		<div id="jadwal"></div>
	</div>

	<div id="right-counter" style="display:none">
		<div class="counter">
			<h1>COUNTER</h1>
			<div class="hh">19<span>JAM</span></div>
			<div class="ii">25<span>MENIT</span></div>
			<div class="ss">45<span>DETIK</span></div>
		</div>
	</div>
	<div id="right-container">
		<div class="main-header-title">
			<h1><?= $db['setting']['nama'] ?></h1>
			<p><?= $db['setting']['lokasi'] ?></p>
		</div>
		<div id="content-slider">
			<?php
			$bumperTimer = isset($db['timer']['bumper']) ? $db['timer']['bumper'] : 3;

			$urutan = isset($db['urutan']) ? $db['urutan'] : [
				'info' => ['order' => 1, 'active' => 1],
				'keuangan' => ['order' => 2, 'active' => 1],
				'donasi' => ['order' => 3, 'active' => 1],
				'kegiatan' => ['order' => 4, 'active' => 1],
				'galeri' => ['order' => 5, 'active' => 1]
			];

			// Sort and filter active modules
			$sorted_modules = [];
			foreach ($urutan as $k => $v) {
				if (is_array($v)) {
					if (isset($v['active']) && $v['active'] == 1) {
						$sorted_modules[$k] = isset($v['order']) ? $v['order'] : 99;
					}
				} else {
					$sorted_modules[$k] = $v;
				}
			}
			asort($sorted_modules);
			$slides_html = [];

			// Build bumper lookup
			$bumpers = [];
			if (isset($db['bumper'])) {
				foreach ($db['bumper'] as $b) {
					$bumpers[$b['id']] = $b;
				}
			}

			// GENERATE BUMPERS
			foreach ($bumpers as $id => $b) {
				ob_start();
			?>
				<div class="content-slide intro-slide" data-timer="<?= $bumperTimer * 1000 ?>">
					<div class="intro-container">
						<div class="intro-icon-wrapper">
							<i class="fa <?= $b['icon'] ?> intro-icon"></i>
						</div>
						<h1 class="intro-title"><?= $b['text'] ?></h1>
					</div>
				</div>
			<?php
				$slides_html[$id] = ob_get_clean();
			}

			// INFO
			ob_start();
			if (!empty($db['info'])):
			?>
				<div class="content-slide" id="slide-info" data-timer="<?= $db['timer']['info'] * 1000 ?>">
					<div class="carousel carousel-fade carousel-zoom quote-carousel slide" data-ride="carousel" data-interval="<?= $db['timer']['info'] * 1000 ?>" data-pause="null" data-wrap="false">
						<div class="carousel-inner">
							<?php
							$i = 0;
							foreach ($db['info'] as $k => $v) {
								if ($v[3]) {
									echo '
								<div class="item slides ' . ($i == 0 ? 'active' : '') . '">
								  <div class="hero">        
									<hgroup>
										<div class="text1">' . htmlentities($v[0]) . '</div>        
										<div class="text2">' . nl2br(htmlentities($v[1])) . '</div>        
										<div class="text3">' . htmlentities($v[2]) . '</div>
									</hgroup>
								  </div>
								</div>
								';
									$i++;
								}
							}
							?>
						</div>
					</div>
				</div>
			<?php endif;
			$slides_html['info'] = ob_get_clean();

			// KEUANGAN
			ob_start();
			if (!empty($db['keuangan'])):
			?>
				<div class="content-slide" id="slide-keuangan" data-timer="<?= $db['timer']['keuangan'] * 1000 ?>">
					<div class="glass-panel keuangan-container" style="height: 100%; display: flex; flex-direction: column; box-sizing: border-box;">
						<div class="panel-header">
							<h2 class="glass-title">Ringkasan Keuangan</h2>
						</div>
						<?php if(!empty($db['laporan_keuangan_img']) && file_exists('keuangan/'.$db['laporan_keuangan_img'])): ?>
							<div style="flex: 1; text-align: center; overflow: hidden; padding: 0 20px 20px 20px;">
								<img src="keuangan/<?= $db['laporan_keuangan_img'] ?>?v=<?=time()?>" style="width: 100%; max-height: 55vh; object-fit: contain; border-radius: 10px;">
							</div>
						<?php else: ?>
						<div class="keuangan-dashboard">
							<?php
							$currentMonth = date('Y-m');
							$kas_awal = 0;
							$kas_masuk = 0;
							$kas_keluar = 0;
							$kas_akhir = 0;

							foreach ($db['keuangan'] as $k) {
								$isPemasukan = $k['jenis'] == 'Pemasukan';
								$nominal = $k['nominal'];
								$trxMonth = date('Y-m', strtotime($k['tanggal']));

								if ($trxMonth < $currentMonth) {
									// Transaksi bulan sebelumnya menjadi Kas Awal bulan ini
									if ($isPemasukan) $kas_awal += $nominal;
									else $kas_awal -= $nominal;
								} elseif ($trxMonth == $currentMonth) {
									// Transaksi bulan ini
									if ($isPemasukan) $kas_masuk += $nominal;
									else $kas_keluar += $nominal;
								}
							}
							$kas_akhir = $kas_awal + $kas_masuk - $kas_keluar;

							$bulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
							$bulan = $bulanIndo[date('n') - 1];
							$tahun = date('Y');
							$periode = strtoupper($bulan . " " . $tahun);
							?>
							<div class="k-hero-saldo">
								<div class="k-hero-top">
									<span class="saldo-label">TOTAL SALDO KAS PER <?= $periode ?></span>
									<h1 class="saldo-amount">Rp <?= number_format($kas_akhir, 0, ',', '.') ?></h1>
								</div>
								<div class="k-hero-bottom">
									<div class="k-stat">
										<div class="k-stat-label">Kas Awal</div>
										<div class="k-stat-val">Rp <?= number_format($kas_awal, 0, ',', '.') ?></div>
									</div>
									<div class="k-stat">
										<div class="k-stat-label">Pemasukan</div>
										<div class="k-stat-val k-masuk">Rp <?= number_format($kas_masuk, 0, ',', '.') ?></div>
									</div>
									<div class="k-stat">
										<div class="k-stat-label">Pengeluaran</div>
										<div class="k-stat-val k-keluar">Rp <?= number_format($kas_keluar, 0, ',', '.') ?></div>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif;
			$slides_html['keuangan'] = ob_get_clean();

			// DONASI
			ob_start();
			if (!empty($db['donasi']['qris']) || !empty($db['donasi']['norek'])):
			?>
				<div class="content-slide" id="slide-donasi" data-timer="<?= $db['timer']['donasi'] * 1000 ?>">
					<div class="glass-panel donasi-container">
						<div class="panel-header">
							<h2 class="glass-title"><i class="fa fa-heart-o"></i> Salurkan Infaq & Shadaqah</h2>
						</div>
						<div class="donasi-grid">
							<?php if (!empty($db['donasi']['qris'])): ?>
								<div class="donasi-qris">
									<div class="qris-wrapper">
										<img src="qris/<?= $db['donasi']['qris'] ?>" class="qris-img">
									</div>
									<p>Scan QRIS untuk Donasi</p>
								</div>
							<?php endif; ?>

							<div class="donasi-bank">
								<div class="bank-card">
									<i class="fa fa-bank bank-icon"></i>
									<h3>Transfer Bank</h3>
									<div class="bank-name"><?= $db['donasi']['bank'] ?></div>
									<div class="bank-rek"><?= $db['donasi']['norek'] ?></div>
									<div class="bank-an">a.n. <?= $db['donasi']['atasnama'] ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif;
			$slides_html['donasi'] = ob_get_clean();

			// KEGIATAN
			ob_start();
			if (!empty($db['kegiatan'])):
			?>
				<div class="content-slide" id="slide-kegiatan" data-timer="<?= $db['timer']['kegiatan'] * 1000 ?>">
					<div class="glass-panel kegiatan-container">
						<div class="panel-header">
							<h2 class="glass-title"><i class="fa fa-calendar-check-o"></i> Agenda Kegiatan Masjid</h2>
						</div>
						<div class="kegiatan-grid-2x2">
							<?php
							$kegiatan = array_slice($db['kegiatan'], 0, 4); // Max 4 items
							$bulanSingkat = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
							foreach ($kegiatan as $k) {
								$t = strtotime($k['tanggal']);
								$d = date('d', $t);
								$m = $bulanSingkat[date('n', $t) - 1]; // Menggunakan bulan singkat Indonesia
								$y = date('Y', $t);
								$waktu = date('H:i', strtotime($k['waktu']));
								echo "
								<div class='kegiatan-card-v2'>
									<div class='k-v2-date-badge'>
										<span class='d'>{$d}</span>
										<span class='m'>{$m}</span>
									</div>
									<div class='k-v2-content'>
										<div class='k-v2-time'><i class='fa fa-clock-o'></i> {$waktu} WIB</div>
										<h3 class='k-v2-title'>{$k['kegiatan']}</h3>
										<p class='k-v2-speaker'>Bersama: <strong>{$k['pemateri']}</strong></p>
									</div>
								</div>";
							}
							?>
						</div>
					</div>
				</div>
			<?php endif;
			$slides_html['kegiatan'] = ob_get_clean();

			// GALERI
			ob_start();
			if (is_dir('galeri')) $galeri = array_diff(scandir('galeri'), array('.', '..', 'Thumbs.db'));
			else $galeri = [];
			if (!empty($galeri)):
			?>
				<div class="content-slide" id="slide-galeri" data-timer="<?= $db['timer']['galeri'] * 1000 ?>">
					<div class="glass-panel galeri-container">
						<div class="carousel carousel-fade galeri-carousel slide" data-ride="carousel" data-interval="<?= $db['timer']['galeri'] * 1000 ?>" data-pause="null" data-wrap="false" id="galeriCarousel">
							<div class="carousel-inner">
								<?php
								$i = 0;
								foreach ($galeri as $v) {
									$ext = strtolower(pathinfo($v, PATHINFO_EXTENSION));
									$active = $i == 0 ? 'active' : '';
									echo '<div class="item slides ' . $active . '">';
									if ($ext == 'mp4') {
										echo '<video src="galeri/' . $v . '" class="galeri-video" muted></video>';
									} else {
										echo '<div class="galeri-img" style="background-image: url(galeri/' . $v . ');"></div>';
									}
									echo '</div>';
									$i++;
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php endif;
			$slides_html['galeri'] = ob_get_clean();

			// Render in the ordered sequence
			foreach ($sorted_modules as $key => $order_num) {
				if (isset($slides_html[$key])) {
					echo $slides_html[$key];
				}
			}
			?>
		</div>
		<div id="running-text">
			<div id="running-text-title">
				<i class="fa fa-bullhorn"></i> Informasi
			</div>
			<div class="item">
				<marquee>
					<?php
					foreach ($db['running_text'] as $k => $v) {
						echo '<i class="fa fa-square-o" aria-hidden="true"></i> ' . htmlentities($v);
					}
					// $ip 	= gethostbyname(php_uname('n'));	// PHP < 5.3.0
					$ip 	= gethostbyname(gethostname());		// PHP >= 5.3.0 ==> di linux keluar 127.0.0.1
					if (PHP_OS == 'Linux') {
						//raspi 3
						// $command="/sbin/ifconfig wlan0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'";//raspi pake wlan0 jadi hotspot
						// $ip = exec ($command);

						//raspi 4
						$command = "/sbin/ifconfig wlan0 | grep 'inet '| cut -d 't' -f2 | cut -d 'n' -f1 | awk '{ print $1}'"; //raspi pake wlan0 jadi hotspot
						$ip = trim(exec($command));
					}
					if ($db['akses']['pass'] == 'admin') {
						echo '<i class="fa fa-square-o" aria-hidden="true"></i> Konek ke wifi (SSID: DisplayMasjid, password: 12345678)';
						echo '<i class="fa fa-square-o" aria-hidden="true"></i> Alamat admin http://' . $ip . '/';
						echo '<i class="fa fa-square-o" aria-hidden="true"></i> Default akses user : admin, password : admin';
						echo '<i class="fa fa-square-o" aria-hidden="true"></i> Silakan mengganti password admin untuk menghilangkan tulisan ini';
					}
					?>
				</marquee>
			</div>
		</div>
	</div>
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/moment-with-locales.js"></script>
	<script src="js/PrayTimes.js"></script>
	<script src="js/jquery.marquee.js"></script>
	<script>
		<?php //Biar nggak ke load di HTML
		// loader 
		// $(window).on('load', function(){ // makes sure the whole site is loaded
		//$('#status').fadeOut(); // will first fade out the loading animation
		// $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
		//$('body').delay(350).css({'overflow':'visible'});
		// })

		// moment.locale('id');
		/*
		Input		Example			Description
		YYYY		2014			4 or 2 digit year
		YY			14				2 digit year
		Y			-25				Year with any number of digits and sign
		Q			1..4			Quarter of year. Sets month to first month in quarter.
		M MM		1..12			Month number
		MMM MMMM	Jan..December	Month name in locale set by moment.locale()
		D DD		1..31			Day of month
		Do			1st..31st		Day of month with ordinal
		DDD DDDD	1..365			Day of year
		X			1410715640.579	Unix timestamp
		x			1410715640579	Unix ms timestamp
		ddd dddd	Mon...Sunday	Day name in locale set by moment.locale()

		H HH		0..23			Hours (24 hour time)
		h hh		1..12			Hours (12 hour time used with a A.)
		k kk		1..24			Hours (24 hour time from 1 to 24)
		a A			am pm			Post or ante meridiem (Note the one character a p are also considered valid)
		m mm		0..59			Minutes
		s ss		0..59			Seconds
		S SS SSS	0..999			Fractional seconds
		Z ZZ		+12:00			Offset from UTC as +-HH:mm, +-HHmm, or Z


		*/
		?>



		//PrayTimes initialize
		var format = '24h';
		<?php
		echo "var lat 		= " . $db['setting']['latitude'] . ";\n";
		echo "var lng 		= " . $db['setting']['longitude'] . ";\n";
		echo "var timeZone 	= " . $db['setting']['timeZone'] . ";\n";
		echo "var dst 		= " . $db['setting']['dst'] . ";\n";


		$prayTimesAdjust	= [];
		if ($db['prayTimesMethod'] == '0') {
			foreach ($db['prayTimesAdjust'] as $k => $v) {
				if ($v != '') $prayTimesAdjust[$k] = $v;
			}
			echo "var prayTimesAdjust =	$.parseJSON('" . stripslashes(str_replace("`", "\\`", json_encode($prayTimesAdjust))) . "');\n";
			// echo "console.log(prayTimesAdjust);\n";
			echo "prayTimes.adjust(prayTimesAdjust);\n";
		} else {
			echo "prayTimes.setMethod('" . $db['prayTimesMethod'] . "');\n";
		}

		$prayTimesTune	= [];
		foreach ($db['prayTimesTune'] as $k => $v) {
			if ($v != '0') $prayTimesTune[$k] = $v;
		}
		if (count($prayTimesTune) > 0) {
			echo "var prayTimesTune =	$.parseJSON('" . stripslashes(str_replace("`", "\\`", json_encode($prayTimesTune))) . "');\n";
			// echo "console.log(prayTimesTune);\n";
			echo "prayTimes.tune(prayTimesTune);\n";
		}
		?>



		//Baris ini ke bawah jika inget nanti pindah ke file terpisah biar rapi......

		var app = {
			db: $.parseJSON(`<?= stripslashes(str_replace("`", "\\`", json_encode($showDb))) ?>`),
			cekDb: false,
			tglHariIni: '',
			tglBesok: '',
			jadwalHariIni: {},
			jadwalBesok: {},
			timer: false,
			// waitAdzanTimer	: false,	// Display countdown sebelum adzan
			adzanTimer: false, // Display adzan
			countDownTimer: false, // Display countdown iqomah
			sholatTimer: false, // Display sholat
			khutbahTimer: false, // Display khutbah
			nextPrayCount: 0, // start next pray count-down
			// nextPrayTimer	: false,	// Display countdown ke sholat selanjutnya
			fajr: '',
			dhuhr: '',
			asr: '',
			maghrib: '',
			isha: '',
			audio: new Audio('img/beep.mp3'),

			initialize: function() {
				app.timer = setInterval(function() {
					app.cekPerDetik()
				}, 1000);
				$('#preloader').delay(350).fadeOut('slow');
				// console.log(app.db);


				// let testTime	= moment().add(8,'seconds');
				// app.runRightCountDown(testTime,'Menuju dzuhur');
				// app.runFullCountDown(testTime,'iqomah',true);
				// app.runFullCountDown(testTime,'TEST COUNTER',false);
				// app.showDisplayAdzan('Dzuhur');
				// app.showDisplayKhutbah();
			},
			cekPerDetik: function() {
				if (!app.tglHariIni || moment().format('YYYY-MM-DD') != moment(app.tglHariIni).format('YYYY-MM-DD')) {
					app.tglHariIni = moment();
					app.tglBesok = moment().add(1, 'days');
					// console.log(app.tglHariIni);
					// console.log(app.tglBesok);
					app.jadwalHariIni = app.getJadwal(moment(app.tglHariIni).toDate());
					app.jadwalBesok = app.getJadwal(moment(app.tglBesok).toDate());
					// console.log(app.jadwalHariIni);
					// console.log(app.jadwalBesok);
					app.fajr = moment(app.jadwalHariIni.fajr, 'HH:mm');
					app.dhuhr = moment(app.jadwalHariIni.dhuhr, 'HH:mm');
					app.asr = moment(app.jadwalHariIni.asr, 'HH:mm');
					app.maghrib = moment(app.jadwalHariIni.maghrib, 'HH:mm');
					app.isha = moment(app.jadwalHariIni.isha, 'HH:mm');
					// console.log('fajr : '+app.fajr.format('YYYY-MM-DD HH:mm:ss'));
					// console.log('dhuhr : '+app.dhuhr.format('YYYY-MM-DD HH:mm:ss'));
					// console.log('asr : '+app.asr.format('YYYY-MM-DD HH:mm:ss'));
					// console.log('maghrib : '+app.maghrib.format('YYYY-MM-DD HH:mm:ss'));
					// console.log('isha : '+app.isha.format('YYYY-MM-DD HH:mm:ss'));
				}
				app.showJadwal();
				app.displaySchedule();
				// app.showCountDownNextPray();
				// app.runRightCountDown(app.dhuhr,'Dzuhur');

				$.ajax({
					type: "POST",
					url: "../proses.php",
					dataType: "json",
					data: {
						id: 'changeDbCheck'
					}
				}).done(function(dt) {
					// console.log(dt.data);
					if (app.cekDb == false) app.cekDb = dt.data;
					else if (app.cekDb !== dt.data) location.reload();
				}).fail(function(msg) {
					console.log(msg);
				});
				// console.log('interval-1000');
			},
			getJadwal: function(jadwalDate) {
				let times = prayTimes.getTimes(jadwalDate, [lat, lng], timeZone, dst, format);
				return times;
			},
			showJadwal: function() {
				// console.log(app.db.prayName)
				// let jamSekarang	= moment().add(9,'months');
				let jamSekarang = moment();
				//+5 menit baru berubah yang aktif (misal sekarang jam dzuhur, di jadwal setelah 5 menit baru berubah yang ashar yang aktif)
				let jamDelay = moment().subtract(5, 'minutes');
				let jadwal = '';
				let hari = app.db.dayName[jamSekarang.format("dddd")]; //pastikan moment js pake standart inggris (default) ==> jangan pindah locale
				let bulan = app.db.monthName[jamSekarang.format("MMMM")];

				// $('#tgl').html(moment().format("dddd, DD MMMM YYYY"));
				$('#jam').html(jamSekarang.format("HH.mm[<div>]ss[</div>]"));
				$('#tgl').html(jamSekarang.format("[" + hari + "], DD [" + bulan + "] YYYY"));

				if ($('.full-screen').is(":visible")) {
					$('#full-screen-clock').html(jamSekarang.format("[<i class='fa fa-clock-o''></i>&nbsp;&nbsp;]HH:mm"));
					$('#full-screen-clock').slideDown();
					console.log('show');
				} else $('#full-screen-clock').slideUp();

				let jadwalDipake = app.jadwalHariIni;
				let jadwalPlusIcon = '';
				//jika diatasa isya' pake jadwal besok

				// console.log(jamSekarang.format('YYYY-MM-DD HH:mm:ss'));
				if (jamDelay > app.isha) {
					jadwalDipakeapp = app.jadwalBesok;
					jadwalPlusIcon = '<span><i class="fa fa-plus" aria-hidden="true"></i></span>';
					// console.log('besok');
				}
				$.each(app.db.prayName, function(k, v) {
					// console.log(jamDelay.format('YYYY-MM-DD HH:mm:ss'));
					let css = '';
					if (k == 'isha' && jamDelay < app.isha && jamDelay > app.maghrib) css = 'active';
					else if (k == 'maghrib' && jamDelay < app.maghrib && jamDelay > app.asr) css = 'active';
					else if (k == 'asr' && jamDelay < app.asr && jamDelay > app.dhuhr) css = 'active';
					else if (k == 'dhuhr' && jamDelay < app.dhuhr && jamDelay > app.fajr) css = 'active';
					else if (k == 'fajr' && (jamDelay < app.fajr || jamDelay > app.isha)) css = 'active'; //diatas isha dan sebelum subuh (beda hari)
					jadwal += '<div class="row ' + css + '"><div class="col-xs-5">' + v + '</div><div class="col-xs-7">' + jadwalDipake[k] + jadwalPlusIcon + '</div></div>';
				});
				$('#jadwal').html(jadwal);
			},
			displaySchedule: function() {
				let now = moment();

				$.each(app.db.prayName, function(k, v) {
					// Waktu Mulai & Selesai untuk setiap tahapan
					let adzanStart = moment(app[k]);
					let waitAdzanStart = moment(adzanStart).subtract(app.db.timer.wait_adzan, 'minutes');
					let iqomahStart = moment(adzanStart).add(app.db.timer.adzan, 'minutes');
					let iqomahEnd = moment(iqomahStart).add(app.db.iqomah[k], 'minutes');

					let isJumat = (now.format('dddd') === 'Friday' && (app.db.jumat.active === true || app.db.jumat.active == 1) && k === 'dhuhr');
					let khutbahEnd = moment(iqomahStart).add(app.db.jumat.duration, 'minutes');

					let sholatStart = isJumat ? khutbahEnd : iqomahEnd;
					let sholatDuration = (k === 'isha' && (app.db.tarawih.active === true || app.db.tarawih.active == 1)) ? app.db.tarawih.duration : app.db.timer.sholat;
					let sholatEnd = moment(sholatStart).add(sholatDuration, 'minutes');

					// Cek posisi waktu sekarang (now) ada di tahap mana, agar tidak reset saat direfresh
					if (now.isBetween(waitAdzanStart, adzanStart, null, '[)')) {
						app.runRightCountDown(adzanStart, 'Menuju ' + v);
					} else if (now.isBetween(adzanStart, iqomahStart, null, '[)')) {
						app.showDisplayAdzan(v, iqomahStart.diff(now));
					} else if (now.isBetween(iqomahStart, sholatStart, null, '[)')) {
						if (isJumat) {
							app.showDisplayKhutbah(sholatStart.diff(now));
						} else {
							app.runFullCountDown(iqomahEnd, 'IQOMAH', true);
						}
					} else if (now.isBetween(sholatStart, sholatEnd, null, '[)')) {
						app.showDisplaySholat(sholatEnd.diff(now));
					}
				});
			},
			getNextPray: function() {
				let jamSekarang = moment();
				let nextPray = 'fajr';
				let jadwalDipake = false;
				if (jamSekarang > app.isha) {
					jadwalDipake = moment(app.jadwalBesok[nextPray], 'HH:mm').add(1, 'Day');
					console.log('jadwal besok');
				} else {
					$.each(app.db.prayName, function(k, v) {
						if (jamSekarang < app[k]) {
							nextPray = k;
							return false;
						}
					});
					jadwalDipake = moment(app.jadwalHariIni[nextPray], 'HH:mm');
				}
				// console.log(jadwalDipake);
				return {
					'pray': nextPray,
					'date': jadwalDipake
				};
			},

			showCountDownNextPray: function() {
				// $('#right-counter').html();
				let nextPray = app.getNextPray();
				if (app.countDownTimer) return; //timer masih jalan
				app.nextPrayCount = 0;
				console.log(moment(nextPray['date']).format('YYYY-MM-DD HH:mm:ss'));

				// Langsung tampilkan agar tidak ada jeda 1 detik (tidak tabrakan)
				$('#right-counter').show();
				$('#right-container').hide();

				app.countDownTimer = setInterval(function() {
					let t = app.countDownCalculate(nextPray.date);

					$('#right-counter .counter>h1').html('Menuju ' + app.db.prayName[nextPray.pray]);
					$('#right-counter .counter>.hh').html(t.hours + '<span>' + app.db.timeName.Hours + '</span>');
					$('#right-counter .counter>.ii').html(t.minutes + '<span>' + app.db.timeName.Minutes + '</span>');
					$('#right-counter .counter>.ss').html(t.seconds + '<span>' + app.db.timeName.Seconds + '</span>');

					app.nextPrayCount++;
					if (app.nextPrayCount >= 30) { // 30 detik show counter
						clearInterval(app.countDownTimer);
						app.countDownTimer = false;
						$('#right-counter').fadeOut();
						$('#right-container').fadeIn();
						// document.getElementById("demo").innerHTML = "EXPIRED";
					}
				}, 1000);
			},
			showDisplayAdzan: function(prayName, remainMs) {
				if (!app.adzanTimer) {
					$('#display-adzan>div').text(prayName);
					$('#display-adzan').show();
					let duration = remainMs !== undefined ? remainMs : (app.db.timer.adzan * 60 * 1000) + 1500;
					app.adzanTimer = setTimeout(function() {
						$('#display-adzan').fadeOut();
						app.adzanTimer = false;
					}, duration);
				}
			},
			showDisplayKhutbah: function(remainMs) {
				if (!app.khutbahTimer) {
					$('#display-khutbah>div').text(app.db.jumat.text);
					$('#display-khutbah').show();
					let duration = remainMs !== undefined ? remainMs : (app.db.jumat.duration * 60 * 1000);
					app.khutbahTimer = setTimeout(function() {
						$('#display-khutbah').fadeOut();
						app.khutbahTimer = false;
						app.showDisplaySholat();
					}, duration);
				}
			},
			showDisplaySholat: function(remainMs) {
				if (!app.sholatTimer) {
					$('#display-sholat').show();
					let duration = remainMs !== undefined ? remainMs : (app.db.timer.sholat * 60 * 1000);
					app.sholatTimer = setTimeout(function() {
						$('#display-sholat').fadeOut();
						app.sholatTimer = false;
						app.showCountDownNextPray();
					}, duration);
				}
			},
			runFullCountDown: function(jam, title, runDisplaySholat) {
				// clearInterval(app.countDownTimer);
				if (app.countDownTimer) return; //timer masih jalan
				app.countDownTimer = setInterval(function() {
					let t = app.countDownCalculate(jam);

					$('#count-down .counter>h1').html(title);
					$('#count-down .counter>.hh').html(t.hours + '<span>' + app.db.timeName.Hours + '</span>');
					$('#count-down .counter>.ii').html(t.minutes + '<span>' + app.db.timeName.Minutes + '</span>');
					$('#count-down .counter>.ss').html(t.seconds + '<span>' + app.db.timeName.Seconds + '</span>');

					$('#count-down').fadeIn();
					if (t.distance == 5) {
						app.audio.play().then(() => {
							// already allowed
						}).catch(() => {
							console.log('Agar beep bunyi ==> permission chrome : sound harus enable');
						});
						// audio.play();
					}
					if (t.distance < 1) {
						clearInterval(app.countDownTimer);
						app.countDownTimer = false;
						$('#count-down').fadeOut();
						if (runDisplaySholat) {
							app.showDisplaySholat();
						}
						// document.getElementById("demo").innerHTML = "EXPIRED";
					}
				}, 1000);
			},
			runRightCountDown: function(jam, title) {
				// $('#right-counter').html();
				if (app.countDownTimer) return; //timer masih jalan

				// Langsung tampilkan agar tidak ada jeda 1 detik (tidak tabrakan)
				$('#right-counter').show();
				$('#right-container').hide();

				app.countDownTimer = setInterval(function() {
					let t = app.countDownCalculate(jam);

					$('#right-counter .counter>h1').html(title);
					$('#right-counter .counter>.hh').html(t.hours + '<span>' + app.db.timeName.Hours + '</span>');
					$('#right-counter .counter>.ii').html(t.minutes + '<span>' + app.db.timeName.Minutes + '</span>');
					$('#right-counter .counter>.ss').html(t.seconds + '<span>' + app.db.timeName.Seconds + '</span>');

					if (t.distance < 1) {
						clearInterval(app.countDownTimer);
						app.countDownTimer = false;
						$('#right-counter').fadeOut();
						$('#right-container').fadeIn();
						// document.getElementById("demo").innerHTML = "EXPIRED";
					}
				}, 1000);
			},
			countDownCalculate(jam) {
				let jamSekarang = moment(); //.subtract(2,'seconds');
				// console.log(jam.format('YYYY-MM-DD HH:mm:ss SSS'));
				// console.log(jamSekarang.format('YYYY-MM-DD HH:mm:ss SSS'));
				// --> jam.diff(jamSekarang, 'seconds') --> convert integer tanpa pembulatan (pembulatan ke bawah)
				let distance = Math.round(jam.diff(jamSekarang, 'seconds', true));
				// console.log(distance);
				let hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
				let minutes = Math.floor((distance % (60 * 60)) / 60);
				let seconds = Math.floor((distance % 60));
				hours = (hours >= 0 && hours < 10) ? '0' + hours : hours;
				minutes = (minutes >= 0 && minutes < 10) ? '0' + minutes : minutes;
				seconds = (seconds >= 0 && seconds < 10) ? '0' + seconds : seconds;
				// console.log(hours);
				return {
					'distance': distance,
					'hours': hours,
					'minutes': minutes,
					'seconds': seconds
				};
			}
		}
		app.initialize();

		var sliderApp = {
			slides: [],
			currentIndex: 0,
			timer: null,
			init: function() {
				$('.content-slide').each(function() {
					$(this).removeClass('active').hide();
					sliderApp.slides.push($(this));
				});
				if (sliderApp.slides.length > 0) {
					sliderApp.playCurrent();
				}
			},
			playCurrent: function() {
				var $currentSlide = sliderApp.slides[sliderApp.currentIndex];
				var timerMs = parseInt($currentSlide.data('timer')) || 10000;

				$('.content-slide').fadeOut(1000);
				setTimeout(function() {
					$currentSlide.fadeIn(1000);
				}, 1000);

				var $carousel = $currentSlide.find('.carousel');

				if ($carousel.length > 0) {
					// Internal carousel (Info / Galeri)
					var handleCarouselItem = function() {
						var $activeItem = $carousel.find('.item.active');
						var isLast = $activeItem.is(':last-child');
						var $video = $activeItem.find('video');

						if ($video.length > 0) {
							$carousel.carousel('pause');
							var vid = $video[0];
							vid.currentTime = 0;

							// Handle autoplay restrictions gracefully
							var playPromise = vid.play();
							if (playPromise !== undefined) {
								playPromise.catch(function(error) {
									console.log("Autoplay prevented or error", error);
									if (vid.onended) vid.onended();
								});
							}

							vid.onended = function() {
								if (isLast) {
									sliderApp.next();
								} else {
									$carousel.carousel('next');
								}
							};
						} else {
							if (isLast) {
								$carousel.carousel('pause'); // Hentikan siklus agar tidak balapan dengan timer pindah konten utama
								sliderApp.timer = setTimeout(function() {
									sliderApp.next();
								}, timerMs);
							} else {
								$carousel.carousel('cycle'); // Lanjutkan siklus antar info
							}
						}
					};

					$carousel.off('slid.bs.carousel').on('slid.bs.carousel', function() {
						handleCarouselItem();
					});

					var currentIndex = $carousel.find('.item.active').index();
					if (currentIndex !== 0) {
						$carousel.carousel(0);
						// handleCarouselItem will be called automatically when sliding finishes
					} else {
						// Already at 0, call it directly
						handleCarouselItem();
					}

				} else {
					// Standalone module
					sliderApp.timer = setTimeout(function() {
						sliderApp.next();
					}, timerMs);
				}
			},
			next: function() {
				clearTimeout(sliderApp.timer);
				sliderApp.currentIndex = (sliderApp.currentIndex + 1) % sliderApp.slides.length;
				sliderApp.playCurrent();
			}
		};

		$(document).ready(function() {
			sliderApp.init();
		});
	</script>
</body>

</html>