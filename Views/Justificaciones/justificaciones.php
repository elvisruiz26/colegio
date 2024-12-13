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
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/justificaciones"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableJustificaciones">
                      <thead>
                        <tr>
                          <th>Nombres</th>
                          <th>Apellidos</th>
                          <th>Estado</th>
                          <th>Fecha</th>
                          <th>Descripcion</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($data['justificaciones']) && is_array($data['justificaciones'])): ?>
                          <?php foreach ($data['justificaciones'] as $justificaciones): ?>
                            <tr>
                              <td><?= $justificaciones['nombres'] ?></td>
                              <td><?= $justificaciones['apellidos'] ?></td>
                              <td><?= $justificaciones['nombre'] ?></td>
                              <td><?= $justificaciones['Fecha'] ?></td>
                              <td><?= $justificaciones['descripcion'] ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7">No hay datos disponibles</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php footerAdmin($data); ?>
