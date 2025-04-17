function compressImg(e, maxSize, ekstensiFile, maxFile=1)
{
  swalLoading();
  setCompressImage(e, readFotoURLnya, maxSize, ekstensiFile, maxFile);
}

const compressImage = async (file, { quality=1, type=file.type, max_width=500, max_height=500 }) => {
    // Get as image data
    const imageBitmap = await createImageBitmap(file);

    // Draw to canvas
    const canvas = document.createElement('canvas');
    var width = imageBitmap.width;
    var height = imageBitmap.height;
    // calculate the width and height, constraining the proportions
    if (width > height) {
      if (width > max_width) {
        //height *= max_width / width;
        height = Math.round(height *= max_width / width);
        width = max_width;
      }
    } else {
      if (height > max_height) {
        //width *= max_height / height;
        width = Math.round(width *= max_height / height);
        height = max_height;
      }
    }
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(imageBitmap, 0, 0, width, height);

    // Turn into Blob
    const blob = await new Promise((resolve) =>
        canvas.toBlob(resolve, type, quality)
    );

    // Turn Blob into File
    return new File([blob], file.name, {
        type: blob.type,
    });
};

// Get the selected file from the file input
const setCompressImage = async (e, readFotoURL=null, maxSize, ekstensiFile, maxFile) => {
  var max_width = 1000;
  var max_height = 1000;
  // Get the files
  const { files } = e.target;

  if (files.length) {
    if (files.length > maxFile) {
      $(e.target).val('');
      setTimeout(function() {
        swalResponse('warning', `Maksimal upload <b>${maxFile}</b> file.`);
      }, 500);
      return false;
    }
    // We'll store the files in this data transfer object
    const dataTransfer = new DataTransfer();

    // For every file in the files list
    for (const file of files) {
        // We don't have to compress files that aren't images
        if (!file.type.startsWith('image')) {
            // Ignore this file, but do add it to our result
            dataTransfer.items.add(file);
            continue;
        }

        // We compress the file by 50%
        const compressedFile = await compressImage(file, {
            quality: 0.5,
            type: 'image/jpeg',
            max_width: max_width,
            max_height: max_height,
        });

        // Save back the compressed file instead of the original file
        dataTransfer.items.add(compressedFile);
    }

    // Set value of the file input to our new files list
    e.target.files = dataTransfer.files;
  }else{
    // No files selected
  }
  if (readFotoURL !== null) {
    readFotoURL(e.target, maxSize, ekstensiFile);
  }
  return true;
}

function readFotoURLnya(input, maxSize, ekstensiFile) {
  var maxSize = (maxSize) ? maxSize:5;
  var ekstensiFile = (ekstensiFile) ? ekstensiFile:['jpeg','jpg','png'];
  if (input.files && input.files[0]) {
    filenya = input.files[0];
    size = filenya.size/1024;
    var fileName = filenya.name;
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if ($.inArray(fileNameExt, ekstensiFile) == -1){
      $(input).val('');
      ekstensinya='';
      for (var i = 0; i < ekstensiFile.length; i++) {
        ekstensinya += ', '+ekstensiFile[i];
      }
      setTimeout(function() {
        swalResponse('warning', `Ekstensi file yang diizinkan <b>${ekstensinya.substr(2)}</b>`);
      }, 500);
      return false;
    }else if (size >= (maxSize*1024)) {
      $(input).val('');
      mb = (maxSize < 1) ? 'KB':'MB';
      sizenya = (mb=='KB') ? (maxSize * 1024):maxSize;
      setTimeout(function() {
        swalResponse('warning', `Maksimal upload <b>${sizenya} ${mb}</b>`);
      }, 500);
      return false;
    }
  }
  setTimeout(function() {
    swalLoading('close');
  }, 1000);
}
