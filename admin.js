


function searchStudent() {
  const id = document.getElementById('search-id').value;
  fetch(`php/search_student.php?student_id=${id}`)
    .then(res => res.json())
    .then(data => {
      const result = document.getElementById('student-result');
      if (data.error) {
        result.innerHTML = `<p>${data.error}</p>`;
      } else {
        const s = data.student;
        const p = data.pass;
        let passInfo = p ? `
          <p><strong>Pass Status:</strong> ${p.status}</p>
          ${p.status === 'pending' ? `<button onclick="releasePass(${p.id})">Release Pass</button>` : ''}
        ` : `<p>No pass found.</p>`;

        result.innerHTML = `
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

const result = document.getElementById('student-result');
if (data.error) {
  result.innerHTML = `<p>${data.error}</p>`;
} else {
  const s = data.student;
  const p = data.pass;

  let passInfo = p ? `
    <p><strong>Pass Status:</strong> ${p.status}</p>
    ${p.status === 'pending' ? `<button onclick="releasePass(${p.id})">Release Pass</button>` : ''}
  ` : `<p><strong>Pass Status:</strong> No pass found.</p>`;

  result.innerHTML = `
    <div class="student-card">
      <h3>${s.name} (${s.id})</h3>
      <p><strong>Room:</strong> ${s.room || '-'}</p>
      <p><strong>Email:</strong> ${s.email}</p>
      <p><strong>Phone:</strong> ${s.phone}</p>
      <p><strong>Nationality:</strong> ${s.nationality}</p>
      ${s.nationality === 'international' ? `<p><strong>Country:</strong> ${s.country}</p>` : ''}
      ${passInfo}
    </div>
  `;
}

function searchByName() {
  const name = document.getElementById('search-name').value;
  fetch(`php/search_by_name.php?name=${name}`)
    .then(res => res.json())
    .then(data => {
      const result = document.getElementById('name-result');
      if (data.length === 0) {
        result.innerHTML = `<p>No students found.</p>`;
      } else {
        result.innerHTML = data.map(s => `
          <div class="student-card">
            <h3>${s.name} (${s.id})</h3>
            <p>Email: ${s.email}</p>
            <p>Phone: ${s.phone}</p>
            <p>Nationality: ${s.nationality}</p>
            ${s.nationality === 'international' ? `<p>Country: ${s.country}</p>` : ''}
          </div>
        `).join('');
      }
    });
}
