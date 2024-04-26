<div class="modal fade" id="modalAddUser" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUserTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12  mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" id="nombres" class="form-control text-uppercase" name="nombres"/>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="user" class="form-label">Nombre de Usuario</label>
                        <input type="email" id="user" class="form-control text-lowercase" name="user" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="password" class="form-label">Contrase&ntilde;a</label>
                        <input type="password" id="password" class="form-control" name="password"/>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="idcaja">Asignar Caja</label>
                        <select class="form-control" id="idcaja" name="idcaja">
                            @foreach ($cashes as $cash)
                                <option value="{{ $cash->id }}">{{ $cash->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditUser" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditUserTitle">Actualizar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12  mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="hidden" name="id">
                        <input type="text" id="nombres" class="form-control text-uppercase" name="nombres"/>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="user" class="form-label">Nombre de Usuario</label>
                        <input type="email" id="user" class="form-control text-lowercase" name="user" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="password" class="form-label">Contrase&ntilde;a</label>
                        <input type="password" id="password" class="form-control" name="password"/>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="idcaja">Asignar Caja</label>
                        <select class="form-control" id="idcaja" name="idcaja">
                            @foreach ($cashes as $cash)
                                <option value="{{ $cash->id }}">{{ $cash->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-store">
                            <span class="text-store">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-storing" role="status"
                                aria-hidden="true"></span>
                            <span class="text-storing d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalUpdateRole" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_update_role" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateRoleTitle">Asignar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label" for="usuario">Nombre</label>
                        <input type="hidden" name="id" />
                        <input type="text" id="usuario" class="form-control text-uppercase" name="usuario" disabled />
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Asignar Rol</label>
                        <div id="wrapper_roles"></div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-update-role">
                            <span class="text-update-role">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 text-saving-role d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving-role d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>