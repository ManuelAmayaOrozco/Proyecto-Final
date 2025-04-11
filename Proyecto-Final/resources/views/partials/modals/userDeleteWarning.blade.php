<form action="{{ route('user.delete', ['id' => $current_user->id]) }}" method="GET" enctype="multipart/form-data">

    <div class="modal fade text-left" id="userDeleteWarning" tabindex="-1" role="dialog" aria-hidden="true">
    
        <div class="modal-dialog modal-lg" role="document">
        
            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title">{{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, Eliminar Usuario</button>

                </div>

            </div>

        </div>

    </div>

</form>