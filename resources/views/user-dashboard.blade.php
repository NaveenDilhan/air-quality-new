<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="profile-container">
    <div class="profile-header">
      <img class="profile-pic" src="profile-pic.jpg" alt="User Profile Picture">
      <h1 class="user-name">John Doe</h1>
      <p class="user-bio">A passionate developer who loves coding and sharing knowledge.</p>
    </div>
    
    <div class="profile-info">
      <h2>User Information</h2>
      <p><strong>Email:</strong> johndoe@example.com</p>
      <p><strong>Location:</strong> New York, USA</p>
      <p><strong>Joined:</strong> January 2022</p>
      <p><strong>Interests:</strong> Technology, Gaming, Reading</p>
    </div>

    <div class="profile-sections">
      <div class="section">
        <h2>Posts</h2>
        <ul>
          <li><a href="#">Post Title 1</a></li>
          <li><a href="#">Post Title 2</a></li>
          <li><a href="#">Post Title 3</a></li>
        </ul>
      </div>

      <div class="section">
        <h2>Achievements</h2>
        <ul>
          <li>Completed 10 Coding Challenges</li>
          <li>Reached Level 5 in the Community Forum</li>
          <li>Shared 5 Tutorials</li>
        </ul>
      </div>
    </div>

    <div class="profile-footer">
      <button onclick="location.href='settings.html'">Settings</button>
      <button onclick="logout()">Logout</button>
    </div>
  </div>

  <script>
    function logout() {
      // Implement logout functionality here
      alert("Logged out!");
    }
  </script>
</body>
</html>
