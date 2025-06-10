<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bảng điều khiển - Thông tin sinh viên</title>
  <style>
    /* Reset and base */
    body {
      font-family: 'Inter', sans-serif, sans-serif;
      background-color: #fafafa;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
    /* Header */
    header {
      background: white;
      border-bottom: 1px solid #e5e7eb;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    nav {
      max-width: 1200px;
      margin: 0 auto;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      padding: 0 16px;
    }
    /* Logo */
    .logo {
      position: absolute;
      left: 16px;
      height: 48px;
      width: 48px;
      object-fit: contain;
    }
    /* Buttons container */
    .nav-buttons {
      display: flex;
      gap: 8px;
    }
    /* Buttons style */
    .nav-button {
      font-size: 0.875rem; /* 14px */
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 0.375rem; /* 6px */
      background: transparent;
      border: none;
      color: #6b7280; /* gray-500 */
      cursor: pointer;
      transition: color 0.2s ease;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .nav-button:hover {
      color: #7c3aed; /* purple-700 */
    }
    .nav-button.active {
      color: #7c3aed;
      background-color: #ddd6fe; /* purple-300 */
    }
    /* Dropdown */
    .dropdown {
      position: relative;
    }
    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.375rem;
      box-shadow: 0 4px 6px rgb(0 0 0 / 0.1);
      margin-top: 4px;
      width: 192px;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.2s ease;
      z-index: 100;
    }
    .dropdown:hover .dropdown-menu {
      opacity: 1;
      visibility: visible;
    }
    .dropdown-menu a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      color: #374151; /* gray-700 */
      font-size: 0.875rem;
    }
    .dropdown-menu a:hover {
      background-color: #f3f4f6; /* gray-100 */
    }
    /* Icons in dropdown */
    .dropdown-menu a .icon {
      width: 16px;
      height: 16px;
      display: inline-block;
      color: #374151;
    }
    /* Main content */
    main {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 16px;
      flex-grow: 1;
    }
    main h1 {
      font-size: 1.5rem;
      font-weight: 400;
      color: #111827; /* gray-900 */
      margin-bottom: 32px;
    }
    /* Student info card */
    .student-card {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
      padding: 32px;
      max-width: 480px;
      margin: 0 auto;
    }
    .student-avatar {
      width: 128px;
      height: 128px;
      border-radius: 9999px;
      object-fit: cover;
      border: 1px solid #d1d5db;
      display: block;
      margin: 0 auto 24px auto;
    }
    .student-name {
      font-size: 1.25rem;
      font-weight: 600;
      color: #111827;
      text-align: center;
      margin-bottom: 4px;
    }
    .student-username {
      text-align: center;
      color: #6b7280;
      margin-bottom: 24px;
      font-size: 1rem;
    }
    form label {
      display: block;
      font-weight: 600;
      color: #374151;
      margin-bottom: 4px;
      font-size: 0.875rem;
    }
    form input,
    form select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      font-size: 1rem;
      color: #111827;
      outline-offset: 2px;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    form input:focus,
    form select:focus {
      border-color: #7c3aed;
      box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.3);
    }
    form input[type="password"] {
      font-family: inherit;
    }
  </style>
</head>
<body>
  <header>
    <nav>
      <a href="#">
        <img class="logo" src="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png" alt="logo_cao_thang" />
      </a>
      <div class="nav-buttons">
        <button id="btn-thongtin" class="nav-button active" type="button">Thông tin sinh viên</button>
        <div class="dropdown">
          <button id="btn-khoahoc" class="nav-button" type="button">
            Khóa Học
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" style="margin-left:4px;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="dropdown-menu" role="menu" aria-label="Khóa Học menu">
            <a href="#" role="menuitem">Anh Văn 1</a>
            <a href="#" role="menuitem">Anh Văn 2</a>
            <a href="#" role="menuitem">Anh Văn 3</a>
            <a href="#" role="menuitem">Anh Văn 2/6</a>
          </div>
        </div>
        <button id="btn-danghoc" class="nav-button" type="button">Đang học</button>
      </div>
    </nav>
  </header>
  <main>
    <h1>Thông tin sinh viên</h1>
    <div class="student-card" role="region" aria-label="Thông tin sinh viên">
      <img class="student-avatar" src="https://storage.googleapis.com/a1aa/image/1684f8f8-5834-474a-712e-da17d035ff9f.jpg" alt="Avatar of the student, a young person with a friendly smile" />
      <h2 class="student-name" id="student-name">Nguyễn Văn A</h2>
      <p class="student-username" id="student-username">@nguyenvana</p>
      <form>
        <div>
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="nguyenvana@example.com" />
        </div>
        <div>
          <label for="password">Password</label>
          <input type="password" id="password" name="password" value="password123" />
        </div>
        <div>
          <label for="birthday">Birthday</label>
          <input type="date" id="birthday" name="birthday" value="2000-01-01" />
        </div>
        <div>
          <label for="gender">Gender</label>
          <select id="gender" name="gender" >
            <option value="" disabled>Select gender</option>
            <option value="male" selected>Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
      </form>
    </div>
  </main>
  <script>
    // Handle active button color switching
    const buttons = [
      document.getElementById('btn-thongtin'),
      document.getElementById('btn-khoahoc'),
      document.getElementById('btn-danghoc')
    ];

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  </script>
</body>
</html>