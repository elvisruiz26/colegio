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
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/listaasistencias"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableAsistenciasGenerales">
                      <thead>
                        <tr>
                          <th>Nombres</th>
                          <th>Apellidos</th>
                          <th>Estado</th>
                          <th>Fecha</th>
                          <th>HoraEntrada</th>
                          <th>HoraSalida</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($data['asistencias']) && is_array($data['asistencias'])): ?>
                          <?php foreach ($data['asistencias'] as $asistencia): ?>
                            <tr>
                              <td><?= $asistencia['nombres'] ?></td>
                              <td><?= $asistencia['apellidos'] ?></td>
                              <td><?= $asistencia['estado'] ?></td>
                              <td><?= $asistencia['Fecha'] ?></td>
                              <td><?= $asistencia['HoraEntrada'] ?></td>
                              <td><?= $asistencia['HoraSalida'] ?></td>
                              <td>
                                <?php if ($asistencia['estado'] == 'Faltó'): ?>
                                  <a href="<?= base_url(); ?>/contacto?fecha=<?= $asistencia['Fecha'] ?>" class="btn btn-primary">Justificar</a>
                                <?php endif; ?>
                              </td>
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
