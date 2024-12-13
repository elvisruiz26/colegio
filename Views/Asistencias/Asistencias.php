<?php 
    headerAdmin($data); 
?>
    <div id="divModal"></div>
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/Asistencias"><?= $data['page_title'] ?></a></li>
        </ul>
        
      </div>
      <div class="row">
        <div class="clearix"></div>
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Cargar archivo (CSV) de asistencias</h3>
            <div class="tile-body">
              <form class="row">
                <div class="mb-3 col-md-3">
                  <label class="form-label">Elegir archivo:</label>
                  <input class="form-control" type="file" id="csvFile" accept=".csv"> 
                </div>
                <div class="mb-3 col-md-4 align-self-end">
                  <button class="btn btn-primary" type="button" onclick="procesarArchivo()"><i class="bi bi-check-circle-fill me-2"></i>Procesar Archivo</button>
                </div>
                <div class="mb-3 col-md-4 align-self-end">
                  <button class="btn btn-primary" type="button" id="exportButton"><i class="bi bi-check-circle-fill me-2"></i>Exportar a Excel</button>
                  <button class="btn btn-primary" type="button" id="exportPdfButton"><i class="bi bi-check-circle-fill me-2"></i>Exportar a PDF</button>
                  <button class="btn btn-primary" type="button" id="uploadButton"><i class="bi bi-check-circle-fill me-2"></i>Subir datos</button>
                </div>
              </form>
              <form id="uploadForm" method="post" action="<?= base_url(); ?>/Asistencias/subirDatos" onsubmit="return false;">
                <input type="hidden" name="docentesData" id="docentesData">
                <input type="hidden" name="stateMapping" id="stateMapping" value='{"Faltó": 2, "Asistió": 1, "Observado": 5, "Tardanza": 3}'>
                <input type="hidden" name="docenteID" id="docenteID"> <!-- Add this line -->
              </form>
            </div>
          </div>
        </div>
      </div>
        </div>
          <table id="result"></table>
        </div>
    </main>

<!-- Asegurarse de incluir 'parsejs.js' antes de 'exportar_excel.js' -->
<script src="<?= media(); ?>/js/parsejs.js"></script>
<script src="<?= media(); ?>/js/exportar_excel.js"></script>
<script src="<?= media(); ?>/js/exportar_pdf.js"></script>

<?php footerAdmin($data); ?>
