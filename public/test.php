<!DOCTYPE html>
<html>
<head>
	<title>Header and Navbar below Main and Footer</title>
	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
			height: 100vh;
			position: relative;
			background-color: #f0f0f0;
		}
		.header {
			height: 50px;
			width: 100%;
			background-color: #ccc;
			position: absolute;
			top: 0;
			left: 0;
			z-index: 1;
		}
		.navbar {
			height: 50px;
			width: 100%;
			background-color: #ddd;
			position: absolute;
			top: 50px;
			left: 0;
			z-index: 1;
		}
		.main {
			padding: 20px;
			background-color: #fff;
			position: relative;
			z-index: 0;
		}
		.footer {
			height: 50px;
			width: 100%;
			background-color: #ccc;
			position: absolute;
			bottom: 0;
			left: 0;
			z-index: 1;
		}
	</style>
</head>
<body>
  <header style="position: relative; z-index: 1;">
    sđá
  </header>
  <nav style="position: relative; z-index: 1;">
   sđâsd
  </nav>
  <main style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 2;">
    sađâsd
  </main>
  <footer style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 2;">
    sađâsd
  </footer>
</body>

</html>
