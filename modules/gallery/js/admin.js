/* Déclaration de la zone de téléchargement des images */
Dropzone.autoDiscover = false;
$('#gallery-dropzone').dropzone({
	dictDefaultMessage: '<div class="text-center"><h2><?php echo icon('fas fa-cloud-upload-alt') ?> DropZone</h2><p class="text-muted">Déposez vos images dans cette zone, ou cliquez ici</p></div>',
	addRemoveLinks: true,
	autoProcessQueue: false,
	parallelUploads: 20,
	init: function() {
		var myDropzone   = this;
		var submitButton = $('#gallery-dropzone-add');
		var progressBar  = $('.progress-bar');
		submitButton.hide();
		$('.upload-infos').hide();
		submitButton.prop('disabled', false);

		/* On lance l'upload sur clic du bouton */
		submitButton.on('click', function() {
			submitButton.html('<i class="fas fa-spinner fa-spin"></i> Téléchargement en cours...');
			submitButton.prop('disabled', true);
			myDropzone.processQueue();
		});

		/* Event: quand un fichier est ajouté, on affiche le bouton d'upload */
		myDropzone.on('addedfile', function() {
			submitButton.show();
			submitButton.html('<?php echo icon('fas fa-cloud-upload-alt') ?> Ajouter les images');
			submitButton.prop('disabled', false);
			$('.label-dropzone').hide();
		});

		/* Event: fichier ajouté, on le retire de la DropZone */
		myDropzone.on('complete', function(file, total_files) {
			myDropzone.removeFile(file);
			if(myDropzone.getQueuedFiles().length > 0 && myDropzone.getUploadingFiles().length > 0) {
				myDropzone.processQueue();
			}
		});

		/* Event: quand un fichier est supprimé */
		myDropzone.on('removedfile', function() {
			if(myDropzone.getQueuedFiles().length == 0 && myDropzone.getUploadingFiles().length == 0) {
				submitButton.hide();
				$('.upload-infos').hide();
				$('.label-dropzone').show();
			}
		});

		/* Event: quand tous les fichiers sont upload, on masque le bouton */
		myDropzone.on('queuecomplete', function() {
			submitButton.hide();
			$('.upload-infos').hide();
			location.reload();
		});

		myDropzone.on('totaluploadprogress', function(totalPercentage, totalBytesToBeSent, totalBytesSent) {
			var sizeInMB     = (totalBytesToBeSent / (1024*1024)).toFixed(2);
			var sentsizeInMB = (totalBytesSent / (1024*1024)).toFixed(2);
			progressBar.css({width:totalPercentage+'%'});
			if(totalPercentage === 100) {
				$('.progress-percent').html('<i class="fas fa-spinner fa-spin"></i> Encore un tout petit instant...');
			} else {
				$('.progress-percent').html('<b><i class="fas fa-spinner fa-spin"></i> '+Math.round(totalPercentage)+'%</b> Veuillez patienter...');
			}
			$('.progress-size').html(sentsizeInMB+'/'+sizeInMB+' Mo');
			$('.upload-infos').show();
		});
	}
});
