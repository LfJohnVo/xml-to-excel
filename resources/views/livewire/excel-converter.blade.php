<div>
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputName">XMLS:</label>
            <input type="file" class="form-control" id="exampleInputName" wire:model="files" multiple required>
            @error('files.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div wire:loading class="py-2">
            <div class="spinner-grow text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-danger" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-info" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div wire:loading.remove class="py-2">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-success">Cargar</button>
        </div>
    </form>
    <div wire:loading.remove>
        <button class="btn btn-warning" wire:click="generarExcel">Generar excel</button>
    </div>
    <br>
    <br>
    @php
        $path = Storage::allFiles('excel');
    @endphp
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Reporte</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @for ($i = 0; $i < count($path); $i++)
                    <th scope="row"><button class="btn btn-primary"
                            wire:click="bajarfile('{{ substr($path[$i], 6) }}')">{{ substr($path[$i], 6) }}</button>
                    </th>
                @endfor
            </tr>
        </tbody>
    </table>
</div>
