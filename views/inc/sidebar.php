<?php
// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<section class="full-box cover dashboard-sideBar">
	<div class="full-box dashboard-sideBar-bg btn-menu-dashboard"></div>
	<div class="full-box dashboard-sideBar-ct">
		<!--SideBar Title -->
		<div class="full-box text-uppercase text-center text-titles dashboard-sideBar-title">
			<?php echo COMPANY; ?> <i class="zmdi zmdi-close btn-menu-dashboard visible-xs"></i>
		</div>
		<!-- SideBar User info -->
		<div class="full-box dashboard-sideBar-UserInfo">
			<figure class="full-box">
				<img style="width: 125px; height: auto;" src="<?php echo SERVERURL; ?>views/assets/img/logo.png" alt="UserIcon">
				<figcaption class="text-center text-titles"><?php echo $_SESSION['userName']; ?></figcaption>
			
			</figure>
			<ul class="full-box list-unstyled text-center">
				<?php //if($_SESSION['userType']=="Administrador"): ?>
				<li>
					<a href="<?php echo SERVERURL; ?>studentinfo/<?php echo $_SESSION['userKey']; ?>/">
						<i class="zmdi zmdi-assignment-account"></i>
					</a>
				</li>

				<li>
					<a href="<?php echo SERVERURL; ?>account/<?php echo $_SESSION['userKey']; ?>/">
						<i class="zmdi zmdi-settings"></i>
					</a>
				</li>
				<?php //endif; ?>
				<li>
					<a href="#!" class="btnFormsAjax" data-action="logout" data-id="form-logout">
						<i class="zmdi zmdi-power"></i>
					</a>
				</li>
			</ul>
			<form action="" id="form-logout" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="token" value="<?php echo $_SESSION['userToken']; ?>">
			</form>
		</div>
		<!-- SideBar Menu -->
		<ul class="list-unstyled full-box dashboard-sideBar-Menu">
			<?php if($_SESSION['userType']=="Administrador"): ?>
			<li>
				<a href="<?php echo SERVERURL; ?>dashboard/">
					<i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Inicio
				</a>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-account zmdi-hc-fw"></i> Administradores <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>admin/">
							<i class="zmdi zmdi-account-add zmdi-hc-fw"></i> Nuevo
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>adminlist/">
							<i class="zmdi zmdi-accounts zmdi-hc-fw"></i> Listado
						</a>
					</li>
					
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-face zmdi-hc-fw"></i> Estudiantes <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>student/">
							<i class="zmdi zmdi-account-circle zmdi-hc-fw"></i> Nuevo
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>studentlist/">
							<i class="zmdi zmdi-male-female zmdi-hc-fw"></i> Listado
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-assignment-check zmdi-hc-fw"></i> Normas <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>normas/">
							<i class="zmdi zmdi zmdi-assignment-alert zmdi-hc-fw"></i> Nuevo
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>normaslist/">
							<i class="zmdi zmdi zmdi-assignment zmdi-hc-fw"></i> Listado
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-balance zmdi-hc-fw"></i> Cursos <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>curso/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Nuevo
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>listacursos/">
							<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Listado
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi-videocam zmdi-hc-fw"></i> Clases <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>class/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Nueva
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>classlist/">
							<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Listado
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>organizacion/">
							<i class="zmdi zmdi-menu zmdi-hc-fw"></i> Organización de Clases
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi zmdi zmdi-gamepad zmdi-hc-fw"></i> Juegos Interactivos <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>juegoadd/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Nuevo
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>juegoslist/">
							<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Listado
						</a>
					</li>
					
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi zmdi zmdi-assignment-check zmdi-hc-fw"></i> Evaluaciones <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>evaluacionadd/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Nueva
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>listaevaluacion/">
							<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Listado
						</a>
					</li>
					
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi zmdi-assignment zmdi-hc-fw"></i> Encuestas <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>encuestaadd/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Nueva
						</a>
					</li>
				
					<li>
						<a href="<?php echo SERVERURL; ?>listaencuesta/">
							<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Listado
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi zmdi-email zmdi-hc-fw"></i> Correos <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>correo/">
							<i class="zmdi zmdi-edit zmdi-hc-fw"></i> Nuevo
						</a>
					</li>
				
					<li>
						<a href="<?php echo SERVERURL; ?>correolist/">
							<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Seguimiento
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#!" class="btn-sideBar-SubMenu">
					<i class="zmdi zmdi zmdi-group zmdi-hc-fw"></i> Reportes <i class="zmdi zmdi-caret-down pull-right"></i>
				</a>
				<ul class="list-unstyled full-box">
					<li>
						<a href="<?php echo SERVERURL; ?>reporteseducacion/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Reporte General
						</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>reportesCurso/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Reporte de Curso
						</a>
					</li>
				
				</ul>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>backup/">
							<i class="zmdi zmdi-tv-alt-play zmdi-hc-fw"></i> Backup
						</a>
			</li>
			<?php else: ?>
			<li>
				<a href="<?php echo SERVERURL; ?>home/">
					<i class="zmdi zmdi-view-dashboard zmdi-hc-fw"></i> Inicio
				</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>videonow/">
					<i class="zmdi zmdi-tv-play zmdi-hc-fw"></i> Clases de hoy
				</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>videolist/">
					<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Listado de clases
				</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>cursolist/">
					<i class="zmdi zmdi-tv-list zmdi-hc-fw"></i> Cursos
				</a>
			</li>
			<?php endif; ?>
		</ul>
	</div>
</section>