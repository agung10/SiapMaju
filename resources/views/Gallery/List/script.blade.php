<script>
	var dataTransfer = new DataTransfer()

	function getFiles(input){
		const files = new Array(input.files.length)
		for(let i = 0; i < input.files.length; i++)
			files[i] = input.files.item(i)
		return files
	}

	function setFiles(input, files){
		const dataTransfer = new DataTransfer()
		for(const file of files)
			dataTransfer.items.add(file)
		input.files = dataTransfer.files
	}

	$(document).ready(function(){
		$('#file-input').on('change', function() { 
		if (window.File && window.FileReader && window.FileList && window.Blob)
		{
			const files = getFiles($(this)[0])
			
			for(const file of files)
				if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ 
					dataTransfer.items.add(file)
				}
			$(this)[0].files = dataTransfer.files
		
			var data = $(this)[0].files

			$.each(data, function(index, file){ 
				if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ 
					var fRead = new FileReader(); 
					fRead.onload = (function(file){ 
					return function(e) {				
						var desc = '<div class="form-group detail-gambar">'+
										'<label>Detail Gambar<span class="text-danger">*</span></label>' +
										`<textarea name="detail_galeri[]" class="form-control detail-galeri" id="exampleTextarea" rows="3"></textarea>`
									'</div>'

						var listContainer = `
						<div class="row list-container-${index + 1} align-items-center">
							<div class="col-md-4">
								<div class="form-group">
									<div>
										<img class="thumb mx-auto d-block" src="${e.target.result}"/>
									</div>
								</div>
							</div>
							<div class="col-md-6">${desc}</div>
						</div>
						<div class="col-md-2">
							<a class="btn font-weight-bolder btn-danger" style="cursor: pointer;" onClick="deleteElement('list-container-${index + 1}', '${file.name}')"><i class="fas fa-trash ml-1"></i></a> 
						</div>`;
						
						if(($(`.list-container-${index + 1}`).length)) return
						$('#imageUploadContainer').append(listContainer);
					};
					})(file);
					fRead.readAsDataURL(file);
				}
			});
		}else{
			alert("Your browser doesn't support File API!");
		}
		});
	});
</script>