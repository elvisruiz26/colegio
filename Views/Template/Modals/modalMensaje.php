<!-- Modal -->
<div class="modal fade" id="modalViewMensaje" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" >
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos del contacto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>ID:</td>
              <td id="celCodigo"></td>
            </tr>
            <tr>
              <td>Nombres:</td>
              <td id="celNombre"></td>
            </tr>
            <tr>
              <td>Email:</td>
              <td id="celEmail"></td>
            </tr>
            <tr>
              <td>Mensaje:</td>
              <td id="celMensaje"></td>
            </tr>
            <tr>
              <td>Fecha a Justificar:</td>
              <td id="celMensajeFecha"></td>
            </tr>
            <tr>
              <td>Observaciones:</td>
              <td><textarea id="txtObservaciones" class="form-control"></textarea></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="idmensaje" value="">
        <input type="hidden" id="idJustificacion" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="btnAprobar" onclick="aprobarMensaje()">Aprobar</button>
        <button type="button" class="btn btn-danger" id="btnRechazar">Rechazar</button>
      </div>
    </div>
  </div>
</div>

