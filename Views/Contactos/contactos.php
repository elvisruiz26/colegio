<?php 
    headerAdmin($data); 
    getModal('modalMensaje',$data);
?>
  <main class="app-content">    
      <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/contactos"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableContactos">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nombre</th>
                          <th>Fecha del Mensaje</th>
                          <th>Fecha a Justificar</th>
                          <th>Mensaje y Fecha</th>
                          <th>Archivo</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php footerAdmin($data); ?>

<!-- Modal -->
<div class="modal fade" id="modalViewMensaje" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle del Mensaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>ID</td>
              <td id="celCodigo"></td>
            </tr>
            <tr>
              <td>Nombre</td>
              <td id="celNombre"></td>
            </tr>
            <tr>
              <td>Email</td>
              <td id="celEmail"></td>
            </tr>
            <tr>
              <td>Fecha</td>
              <td id="celFecha"></td>
            </tr>
            <tr>
              <td>Mensaje</td>
              <td id="celMensaje"></td>
            </tr>
            <tr>
              <td>Archivo</td>
              <td id="celArchivo"></td>
            </tr>
            <tr>
              <td>Descargar Archivo</td>
              <td><a id="celArchivoDescarga" href="#" target="_blank" class="btn btn-primary">Descargar</a></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
