@if (count($question->answer) > 0)
    @foreach ($question->answer as $key => $value)
        <div class="custom-radio-info">
            <input type="radio" name="id_pilih_jawaban" id="{{ $value->id_pilih_jawaban }}" class="radio" value="{{ $value->id_pilih_jawaban }}" {{ ((!empty($answerID)) ? (($answerID == $value->id_pilih_jawaban) ? ('checked') : ('')) : ('')) }} />
            <label class="err-radio-label" for="{{ $value->id_pilih_jawaban }}">{{ $value->isi_pilih_jawaban }}</label>
        </div>
    @endforeach
@endif