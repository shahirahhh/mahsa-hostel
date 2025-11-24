<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
 <script src="admin.js"></script>
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

<h2>Search Student by ID</h2>
<input type="text" id="search-id" placeholder="Enter Student ID">
<button onclick="searchStudent()">Search</button>

<h2>Search Student by Name</h2>
<input type="text" id="search-name" placeholder="Enter Student Name">
<button onclick="searchByName()">Search</button>
<div id="name-result"></div>

<div id="student-result"></div>
<script src="js/admin.js"></script>
</body>

<form action="php/export_students.php" method="POST">
  <button type="submit">Download Student List (CSV)</button>
</form>

<?php
include 'db.php';

// Total students
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];

// Pending passes
$pending_passes = $conn->query("SELECT COUNT(*) AS total FROM passes WHERE status='pending'")->fetch_assoc()['total'];
?>

<div class="dashboard-summary">
  <h3>Dashboard Summary</h3>
  <p>Total Students: <?php echo $total_students; ?></p>
  <p>Pending Passes: <?php echo $pending_passes; ?></p>
</div>

<script>
function searchStudent() {
  const id = document.getElementById('search-id').value;
  fetch(`php/search_student.php?student_id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        document.getElementById('student-result').innerHTML = `<p>${data.error}</p>`;
      } else {
        const s = data.student;
        const p = data.pass;
        let passInfo = p ? `
          <p><strong>Pass Status:</strong> ${p.status}</p>
          ${p.status === 'pending' ? `<button onclick="releasePass(${p.id})">Release Pass</button>` : ''}
        ` : `<p>No pass found.</p>`;

        document.getElementById('student-result').innerHTML = `
          <h3>${s.name} (${s.id})</h3>
          <p><strong>Room:</strong> ${s.room}</p>
          <p><strong>Email:</strong> ${s.email}</p>
          <p><strong>Phone:</strong> ${s.phone}</p>
          <p><strong>Nationality:</strong> ${s.nationality}</p>
          ${s.nationality === 'international' ? `<p><strong>Country:</strong> ${s.country}</p>` : ''}
          ${passInfo}
        `;
      }
    });
}
</script>

<?php
include 'db.php';
$today = date('Y-m-d');
$sql = "SELECT * FROM passes WHERE DATE(created_at) = '$today'";
$result = $conn->query($sql);

echo "<h3>Passes Today</h3><table><tr><th>Student ID</th><th>Status</th><th>Time</th></tr>";
while ($row = $result->fetch_assoc()) {
  echo "<tr><td>{$row['student_id']}</td><td>{$row['status']}</td><td>{$row['created_at']}</td></tr>";
}
echo "</table>";
?>


<script>
function releasePass(passId) {
  fetch('php/release_pass.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${passId}`
  })
  .then(res => res.text())
  .then(msg => {
    alert(msg);
    searchStudent(); // refresh info
  });
}
</script>
</html>



