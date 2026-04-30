<?php require_once 'config.php'; if(!isset($_SESSION['user_id'])) header("Location: login.php");
 $settings=$mysqli->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
 $schedulesRes=$mysqli->query("SELECT s.*, sd.name as sound_name FROM schedules s LEFT JOIN sounds sd ON s.sound_id = sd.id ORDER BY s.time ASC");
 $soundsRes=$mysqli->query("SELECT * FROM sounds");
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
<style>:root{--bg:#0f172a;--card:#1e293b;--border:#334155;--accent:#3b82f6}body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:#f1f5f9;margin:0;min-height:100vh}
.container{max-width:1200px;margin:0 auto;padding:20px}.header-admin{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;border-bottom:1px solid var(--border);padding-bottom:20px}
.grid{display:grid;grid-template-columns:300px 1fr;gap:20px}@media(max-width:768px){.grid{grid-template-columns:1fr}}
.card{background:var(--card);border-radius:12px;padding:20px;border:1px solid var(--border)}
.card h3{margin-top:0;border-bottom:1px solid var(--border);padding-bottom:10px;display:flex;justify-content:space-between;align-items:center}
input,select{width:100%;padding:10px;background:var(--bg);border:1px solid var(--border);border-radius:8px;color:white;margin-bottom:10px;box-sizing:border-box}
label{font-size:12px;color:#94a3b8;display:block;margin-bottom:5px}
.btn{padding:10px 20px;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:14px;transition:0.2s;text-decoration:none}
.btn-primary{background:var(--accent);color:white}.btn-danger{background:#ef4444;color:white}.btn-ghost{background:transparent;border:1px solid var(--border);color:#94a3b8}
table{width:100%;border-collapse:collapse;margin-top:10px}th,td{text-align:left;padding:12px;border-bottom:1px solid var(--border);font-size:14px}
.badge{padding:4px 8px;border-radius:4px;font-size:10px;text-transform:uppercase}
.badge-blue{background:rgba(59,130,246,0.2);color:#60a5fa}.badge-yellow{background:rgba(245,158,11,0.2);color:#fbbf24}.badge-green{background:rgba(16,185,129,0.2);color:#34d399}.badge-red{background:rgba(239,68,68,0.2);color:#f87171}
.modal{position:fixed;inset:0;background:rgba(0,0,0,0.8);display:none;align-items:center;justify-content:center;z-index:100}.modal.active{display:flex}
.modal-content{background:var(--card);padding:25px;border-radius:12px;width:90%;max-width:500px}
.switch{position:relative;display:inline-block;width:50px;height:26px}.switch input{opacity:0;width:0;height:0}
.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#334155;transition:.4s;border-radius:34px}
.slider:before{position:absolute;content:"";height:18px;width:18px;left:4px;bottom:4px;background-color:white;transition:.4s;border-radius:50%}
input:checked+.slider{background-color:var(--accent)}input:checked+.slider:before{transform:translateX(24px)}
.days-picker{display:flex;gap:5px;margin-bottom:15px}.day-btn{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:var(--bg);border:1px solid var(--border);border-radius:8px;cursor:pointer;font-size:12px}
.day-btn.active{background:var(--accent);border-color:var(--accent);color:white;font-weight:bold}</style></head>
<body><div class="container"><div class="header-admin"><h2>Panel Admin</h2><div style="display:flex;gap:10px;align-items:center">
<a href="index.php" target="_blank" class="btn btn-ghost">Lihat Display</a><a href="logout.php" class="btn btn-danger" style="padding:8px 15px">Logout</a></div></div>
<div class="grid"><div class="sidebar"><div class="card" style="margin-bottom:20px"><h3>Pengaturan</h3><form id="settingsForm">
<label>Nama Sekolah</label><input type="text" id="school_name" value="<?= htmlspecialchars($settings['school_name'])?>">
<label>Alamat</label><input type="text" id="school_address" value="<?= htmlspecialchars($settings['school_address'])?>">
<label>Logo</label><input type="file" id="logo_upload" accept="image/*"><button type="button" onclick="uploadLogo()" class="btn btn-ghost" style="width:100%;margin-bottom:10px">Upload Logo</button>
<div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px"><span>Sistem Aktif</span>
<label class="switch"><input type="checkbox" id="system_active" <?= $settings['system_active']?'checked':''?>><span class="slider"></span></label></div>
<button type="submit" class="btn btn-primary" style="width:100%;margin-top:15px">Simpan</button></form></div>
<div class="card"><h3>Suara Bel</h3><div style="max-height:200px;overflow-y:auto"><?php while($s=$soundsRes->fetch_assoc()):?><div style="display:flex;justify-content:space-between;padding:8px;background:rgba(0,0,0,0.2);border-radius:5px;margin-bottom:5px;font-size:13px">
<span><?= $s['name']?></span><a href="<?= $s['file_path']?>" target="_blank" style="color:var(--accent)">Play</a></div><?php endwhile;?></div>
<hr style="border-color:var(--border);margin:15px 0"><input type="text" id="sound_name" placeholder="Nama Suara"><input type="file" id="sound_file" accept="audio/*">
<button onclick="uploadSound()" class="btn btn-ghost" style="width:100%">Upload Suara Baru</button></div></div>
<div class="main"><div class="card"><h3>Jadwal Pelajaran<button onclick="openModal()" class="btn btn-primary" style="padding:8px 15px">+ Tambah</button></h3>
<table><thead><tr><th>Waktu</th><th>Kegiatan</th><th>Jenis</th><th>Hari</th><th>Suara</th><th>Aksi</th></tr></thead><tbody>
<?php 
 $schedulesRes->data_seek(0);$daysMap=['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
while($row=$schedulesRes->fetch_assoc()):$daysStr='';foreach(explode(',',$row['days']) as $d)$daysStr.=$daysMap[$d].', ';$daysStr=rtrim($daysStr,', ');
 $typeClass='badge-blue';if($row['type']=='masuk')$typeClass='badge-green';if($row['type']=='pulang')$typeClass='badge-red';if($row['type']=='istirahat')$typeClass='badge-yellow';?>
<tr><td style="font-family:monospace;font-weight:bold;color:var(--accent)"><?= date('H:i',strtotime($row['time']))?></td>
<td><?= htmlspecialchars($row['name'])?></td><td><span class="badge <?= $typeClass?>"><?= $row['type']?></span></td>
<td style="font-size:12px;opacity:0.8"><?= $daysStr?></td><td style="font-size:12px;opacity:0.8"><?= $row['sound_name']??'Default'?></td>
<td><button onclick='editSchedule(<?= json_encode($row)?>)' class="btn btn-ghost" style="padding:5px 10px">Edit</button>
<button onclick="deleteSchedule(<?= $row['id']?>)" class="btn btn-danger" style="padding:5px 10px">Hapus</button></td></tr><?php endwhile;?>
</tbody></table></div></div></div></div>
<div id="scheduleModal" class="modal"><div class="modal-content"><h3 style="margin-top:0" id="modalTitle">Tambah Jadwal</h3><form id="scheduleForm">
<input type="hidden" id="edit_id"><div style="display:grid;grid-template-columns:1fr 1fr;gap:10px"><div><label>Waktu</label><input type="time" id="input_time" required></div>
<div><label>Jenis</label><select id="input_type"><option value="masuk">Masuk</option><option value="ganti">Ganti Jam</option><option value="istirahat">Istirahat</option><option value="pulang">Pulang</option></select></div></div>
<label>Nama Kegiatan</label><input type="text" id="input_name" required><label>Pilih Hari</label><div class="days-picker" id="dayPicker"></div>
<label>Pilih Suara</label><select id="input_sound"><?php $soundsRes->data_seek(0);while($s=$soundsRes->fetch_assoc()):?><option value="<?= $s['id']?>"><?= $s['name']?></option><?php endwhile;?></select>
<div style="display:flex;gap:10px;margin-top:20px"><button type="button" onclick="closeModal()" class="btn btn-ghost" style="flex:1">Batal</button><button type="submit" class="btn btn-primary" style="flex:1">Simpan</button></div></form></div></div>
<script>
const days=['Min','Sen','Sel','Rab','Kam','Jum','Sab'];let selectedDays=[];
function initDayPicker(a=[1,2,3,4,5]){const c=document.getElementById('dayPicker');c.innerHTML='';selectedDays=a;days.forEach((d,i)=>{const e=document.createElement('div');e.className='day-btn'+(a.includes(i)?' active':'');e.innerText=d;e.onclick=()=>{e.classList.toggle('active');selectedDays=Array.from(document.querySelectorAll('#dayPicker .day-btn.active')).map(el=>days.indexOf(el.innerText))};c.appendChild(e)})}
function openModal(){document.getElementById('modalTitle').innerText="Tambah Jadwal";document.getElementById('edit_id').value='';document.getElementById('scheduleForm').reset();initDayPicker();document.getElementById('scheduleModal').classList.add('active')}
function closeModal(){document.getElementById('scheduleModal').classList.remove('active')}
function editSchedule(d){document.getElementById('modalTitle').innerText="Edit Jadwal";document.getElementById('edit_id').value=d.id;document.getElementById('input_time').value=d.time.substring(0,5);document.getElementById('input_type').value=d.type;document.getElementById('input_name').value=d.name;document.getElementById('input_sound').value=d.sound_id||'';initDayPicker(d.days);document.getElementById('scheduleModal').classList.add('active')}
async function api(a,d={}){const r=await fetch('api.php?action='+a,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});return r.json()}
document.getElementById('scheduleForm').addEventListener('submit',async e=>{e.preventDefault();await api('save_schedule',{id:document.getElementById('edit_id').value,time:document.getElementById('input_time').value,type:document.getElementById('input_type').value,name:document.getElementById('input_name').value,sound_id:document.getElementById('input_sound').value,days:selectedDays});location.reload()});
async function deleteSchedule(id){if(confirm('Yakin hapus?')){await api('delete_schedule',{id:id});location.reload()}}
document.getElementById('settingsForm').addEventListener('submit',async e=>{e.preventDefault();await api('save_settings',{school_name:document.getElementById('school_name').value,school_address:document.getElementById('school_address').value,system_active:document.getElementById('system_active').checked});alert('Tersimpan!');location.reload()});
async function uploadLogo(){let f=document.getElementById('logo_upload').files[0];if(!f)return;let fd=new FormData();fd.append('file',f);fd.append('type','logo');await fetch('api.php',{method:'POST',body:fd});alert('Logo diupload!');location.reload()}
async function uploadSound(){let f=document.getElementById('sound_file').files[0];let n=document.getElementById('sound_name').value;if(!f||!n)return alert('Lengkapi data');let fd=new FormData();fd.append('file',f);fd.append('type','sound');fd.append('name',n);await fetch('api.php',{method:'POST',body:fd});alert('Suara diupload!');location.reload()}
initDayPicker();
</script></body></html>