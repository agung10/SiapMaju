<select name="{{ $name }}" class="form-control @isset($class) {{ $class }} @endisset">
  <option></option>
    @foreach($data as $key => $value)
        <option value="{{ $key }}" 
          @if(isset($selected) && !is_array($selected) && $selected== $key)
            selected
          @elseif(isset($selected) && is_array($selected) && in_array($key, $selected))
            selected
          @endif
        > 
          {!! $value !!} 
        </option>
    @endforeach
</select>

<script>
  $(function() {
    let placeholder = "{{ \Lang::get('-- Pilih '. $title . ' --') }}"

    @isset($placeholder)
      placeholder = "{{ $placeholder }}"
    @endisset

    $("select[name='{{ $name }}']").select2({ 
      placeholder: placeholder, 
      width: '100%'
    });

    $("select[name='{{ $name }}']").on("select2:close", function (e) {  
        $(this).valid(); 
    });
  })
</script>