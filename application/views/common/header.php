<style>
.headerBox {
  background-color: skyblue;
  display: flex;
  flex-direction: column; 
  align-items: center; 
  justify-content: center;
  height: 200px; 
  text-align: center; 
}

.header-ul {
  list-style: none; 
  padding: 0;
  margin: 50px 0px 50px 0px; 
}

.header-ul li {
  display: inline; 
  margin: 0 10px; 
}

.header-ul a {
  text-decoration: none; 
}
</style>

<div class="headerBox mb-3 d-flex">
  <h1 class="fw-bold my-auto d-block">CodeIgniter simple project</h1>
  <ul class="header-ul">
    <li><a href="/">홈</a></li>
    <li><a href="board">게시판</a></li>
    <li>
      <?php if (isset($_COOKIE["id"])) { ?>
      <a href="logout">로그아웃</a>
      <li><a href="mypage">마이페이지</a></li>
    <?php } else { ?>
      <a href="login">로그인</a>
    <?php } ?>
    </li>
  </ul>
</div>