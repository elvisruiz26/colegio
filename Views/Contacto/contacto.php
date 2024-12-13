<?php 
    headerAdmin($data); 
?>

<main class="app-content">    
      <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/contacto"><?= $data['page_title'] ?></a></li>
        </ul>
	</div>
	<!-- Content page -->
	<section class="bg0 p-t-104 p-b-116">
		<div class="container">
			<div class="flex-w flex-tr">
				<div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
					<form id="frmContacto" action="<?= base_url(); ?>/contacto/enviar" method="POST" enctype="multipart/form-data">
						<h4 class="mtext-105 cl2 txt-center p-b-30">
							Enviar evidencia
						</h4>

						<div class="bor8 m-b-20">
							<textarea class="stext-111 cl2 plh3 size-120 p-lr-28 p-tb-25" id="mensaje" name="mensaje" placeholder="Escribe tu justificación"></textarea>
						</div>

						<div class="bor8 m-b-20">
							<?php
								$fecha = isset($_GET['fecha']) ? DateTime::createFromFormat('d/m/Y', $_GET['fecha'])->format('Y-m-d') : '';
							?>
							<input type="date" id="fecha" name="fecha" value="<?= $fecha ?>">
						</div>

						<div class="bor8 m-b-30">
							<input type="file" id="archivo" name="archivo">
						</div>

						<input type="hidden" id="idstatus" name="idstatus" value="2">

						<button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
							Enviar
						</button>
					</form>
				</div>

				<div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-map-marker"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								Dirección
							</span>

							<p class="stext-115 cl6 size-213 p-t-18">
								<?= DIRECCION ?>
							</p>
						</div>
					</div>

					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-phone-handset"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								Teléfono
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">

								<a class="" href="tel:<?= TELEMPRESA ?>"><?= TELEMPRESA ?></a>
							</p>
						</div>
					</div>

					<div class="flex-w w-full">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-envelope"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								E-mail
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">
								<a class="" href="mailto:<?= EMAIL_EMPRESA ?>"><?= EMAIL_EMPRESA ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php footerAdmin($data); ?>

<!-- Incluir las librerías necesarias antes de cerrar el body -->
<script>
    const base_url = "<?= base_url(); ?>";
</script>
<!-- Incluir la librería SweetAlert si no se incluye en footerAdmin -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- Si functions_contacto.js ya se incluye en footerAdmin, no es necesario incluirlo aquí -->
<!-- <script src="<?= media(); ?>/js/functions_contacto.js"></script> -->

<!-- Añadir divLoading si es necesario -->
<div id="divLoading">
    <div>
        <img src="<?= media(); ?>/images/loading.svg" alt="Loading">
    </div>
</div>