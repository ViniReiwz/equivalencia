<fieldset class="border rounded p-3 mb-3">
  <legend class="h5 w-auto px-2">Tipo de aproveitamento</legend>

  <input type="hidden" name="equivalente" value="0">
  <div class="d-flex align-items-center">
    <span id="{{ $formId }}-equivalente-text" class="mr-3 js-equivalencia-tipo-text">
      {{ $formState['administrative']['equivalente'] ? 'Equivalente' : 'Não equivalente' }}
    </span>
    <div class="custom-control custom-switch">
      <input type="checkbox" class="custom-control-input js-equivalencia-tipo" id="{{ $formId }}-equivalente"
        name="equivalente" value="1" aria-labelledby="{{ $formId }}-equivalente-text"
        @checked($formState['administrative']['equivalente'])>
      <label class="custom-control-label" for="{{ $formId }}-equivalente">
        <span class="sr-only">Alternar tipo de aproveitamento</span>
      </label>
    </div>
  </div>
</fieldset>

<fieldset class="border rounded p-3 mb-3">
  <legend class="h5 w-auto px-2">Dados da reunião</legend>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="{{ $formId }}-numero-reuniao">Número da reunião</label>
      <input type="number" class="form-control" id="{{ $formId }}-numero-reuniao" name="numero_reuniao"
        value="{{ $formState['administrative']['numero_reuniao'] }}" step="1">
    </div>
    <div class="form-group col-md-6">
      <label for="{{ $formId }}-data-reuniao">Data da reunião</label>
      <input type="date" class="form-control" id="{{ $formId }}-data-reuniao" name="data_reuniao"
        value="{{ $formState['administrative']['data_reuniao'] }}">
    </div>
  </div>

  <div class="form-group mb-0">
    <label for="{{ $formId }}-observacoes">Observações</label>
    <textarea class="form-control" id="{{ $formId }}-observacoes" name="observacoes"
      rows="3">{{ $formState['administrative']['observacoes'] }}</textarea>
  </div>
</fieldset>
