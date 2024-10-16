function showFields() {
            var selectedType = document.querySelector('select[name="typeOffre"]').value;
            var sections = document.querySelectorAll('.section');
            sections.forEach(function(section) {
                section.style.display = 'none'; 
            });

            if (selectedType) {
                document.getElementById(selectedType).style.display = 'block';
            }
        }



const MAX_IMAGES = 5;

        function handleDrop(event) {
            event.preventDefault();
            var files = event.dataTransfer.files;
            if (files.length > 0) {
                previewImages(files);
            }
        }

        function handleDragOver(event) {
            event.preventDefault();
            var dropZone = document.getElementById('dropZone');
            dropZone.classList.add('hover');
        }

        function handleDragLeave(event) {
            var dropZone = document.getElementById('dropZone');
            dropZone.classList.remove('hover');
        }

        function openFileDialog() {
            document.getElementById('fileInput').click();
        }

        window.onload = function() {
            var dropZone = document.getElementById('dropZone');
            dropZone.addEventListener('drop', handleDrop);
            dropZone.addEventListener('dragover', handleDragOver);
            dropZone.addEventListener('dragleave', handleDragLeave);
            dropZone.addEventListener('click', openFileDialog);

            document.getElementById('fileInput').addEventListener('change', function(event) {
                previewImages(this.files);
            });
        };


            function previewImages(files) {
                if (imageCount > 0) {
                    ;
                }
            }
        