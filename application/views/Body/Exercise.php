<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="row">
			<div class="col-sm-12"><?php

			echo $question;?>

			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 exercise_input">
				<form id="exercise_form" autocomplete="off"><?php

					switch ($type) {
						case 'int':
							$this->load->view('Input/Default');
							break;
						case 'text':
							$this->load->view('Input/Default');
							break;
						case 'multi':
							$this->load->view('Input/Multi', array('options' => $options));
							break;
						case 'division':
							$this->load->view('Input/Division');
							break;
						case 'fraction':
							$this->load->view('Input/Fraction');
							break;
						case 'equation2':
							$this->load->view('Input/Equation2');
							break;
						case 'quiz':
							$this->load->view('Input/Quiz',
								array('options' => $options,
										'width' => $width,
										'align' => $align));
							break;
					}?>

					<input type="hidden" name="hash" value="<?php echo $hash;?>">
					<input type="hidden" name="hints_all" value="<?php echo $hints_all;?>">
					<div class="text-center"><?php

						if ($this->Session->CheckLogin()) {

							echo $type.'<br />';
							echo json_encode($correct).'<br />';

						}?>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<a class="btn btn-default pull-left" href="<?php echo base_url().'view/subtopic/'.$subtopicID.'/'.$id;?>">
					<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Vissza
				</a>
				<a id="next_button" class="btn btn-primary pull-right" onclick="checkSolution(event)">
					Tovább&nbsp;<span class="glyphicon glyphicon-chevron-right">
				</a><br /><br /><?php

				if ($hints_all > 0) {?>

					<p><a id="hint_button" class="btn btn-danger pull-right" onclick="gethint(event)">
						Segítséget kérek!
					</a></p><br />
					<p id="hints_left" class="small pull-right">
						(<?php echo $hints_all-$hints_used;?> segítség maradt.)
					</p><?php

				}

				?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<div id="message"></div>
			</div>
		</div>
		<div class="row">
			<div id="explanation" class="col-sm-12"></div>
		</div>
		<div class="row"><?php

			if ($this->Session->CheckLogin() && isset($explanation)) {?>

			<div class="col-sm-12"><?php

				foreach ($explanation as $hint) {
					// print_r('<p>'.$hint.'</p>');
				}?>

			</div><?php

			}?>
		</div>
	</div>
	<div class="col-md-2"></div>
</div>

<script>

	document.onkeypress = keyPress;

	function keyPress(e){
		var x = e || window.event;
		var key = (x.keyCode || x.which);
		if(key == 13 || key == 3){
			if ($("#next_button").attr('href')) {
				window.location.href = $("#next_button").attr('href');
			} else {
				checkSolution(x);
			}
		}
	}

	function gethint(event, id, type){
		var hash = $('[name="hash"]').attr('value');
		var hints_all = $('[name="hints_all"]').attr('value');

		if (typeof id === 'undefined') {
			id = "";
		}
		if (typeof type === 'undefined') {
			type = "";
		}
		event.preventDefault();
		if (id == "" || (id > 0 && id <= hints_all)) {
		$.ajax({
			type: "GET",
			url: "<?php echo base_url();?>application/gethint/"+hash+"/"+id.toString()+"/"+type.toString(),
			success: function(data) {
				if (data != "null") {
					$("#message").html('');
					var data = jQuery.parseJSON(data);
					var hint_current = Number(data['hint_current']);
					var hints_all = Number(data['hints_all']);
					var hints_used = Number(data['hints_used']);
					var hints_left = hints_all - hints_used;
					if (hints_all > 1) {
						$("#explanation").html('<ul class="pager"></ul>');
						$("#explanation").children().append('<li class="prev_hint small"><a onclick="gethint(event,'+hint_current+',\'prev\')"><span class="glyphicon glyphicon-chevron-left"></span></a></li>');
						if (hint_current == 1) {
							$(".prev_hint").attr('class', 'small disabled');
						}
						$("#explanation").children().append('<li class="small"><b>'+hint_current+'/'+hints_all+'</b></li>');
						$("#explanation").children().append('<li class="next_hint small"><a onclick="gethint(event,'+hint_current+',\'next\')"><span class="glyphicon glyphicon-chevron-right"></span></a></li>');
						if (hint_current >= hints_all) {
							$(".next_hint").attr('class', 'small disabled');
						}
					}
					if (data['explanation'] != '') {
						$("#explanation").append('<p>'+data['explanation']+'</p>');
						MathJax.Hub.Queue(["Typeset",MathJax.Hub,"explanation"]);
						$("#hints_left").html("("+hints_left.toString()+" segítség maradt.)");
						if (hints_left == 0) {
							$("#hint_button").attr('class', 'btn btn-danger pull-right disabled');
						}
					}
				}
			}
		});
		}
	}

	// Check solution
	function checkSolution(event) {
		var queryString = $("#exercise_form").serializeArray();
		event.preventDefault();
		$.ajax({
			type: "GET",
			url: "<?php echo base_url();?>application/checkanswer",
			data: {
				answer: JSON.stringify(queryString)
			},
			dataType: "json",
			success: function(data) {
				
				// Exercise not finished
				if (data['status'] == 'NOT_DONE') {
					$("#message").html('<div class="alert alert-warning"><strong><span class=\"glyphicon glyphicon-remove\"></span></strong>&nbsp;&nbsp;'+data['message']+'</div>');
					MathJax.Hub.Queue(["Typeset",MathJax.Hub,"message"]);
					return;
				}

				// Disable buttons
				var radios = document.forms["exercise_form"]["answer"];

				if (radios.length > 0) {
					for (var i=0, iLen=radios.length; i<iLen; i++) {
						radios[i].disabled = true;
					}
				} else {
					radios.disabled = true;
				}

				// Disable hint button
				$("#hint_button").attr('class',"btn btn-danger pull-right disabled");

				// Exercise finished
				switch (data['status']) {
					case 'CORRECT':
						$("#message").replaceWith('<div class="alert alert-success"><strong><span class=\"glyphicon glyphicon-ok\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>');
						$('#progress_bar').css('width', data['progress']['value']+'%').attr('aria-valuenow', data['progress']['value']);
						progress_bar_class = $('#progress_bar').attr('class').replace(/(progress-bar-)\w*/, '$1'+ data['progress']['style']);
						$('#progress_bar').attr('class', progress_bar_class);
						if (data['id_next'] == null) {
							$("#next_button").replaceWith("<a id=\"next_button\" class=\"btn btn-primary pull-right\" href=\"<?php echo base_url().'view/subtopic/';?>" + data['subtopicID'] + '/' + data['questID'] + "\">Kész! :)</button>");
						} else {
							$("#next_button").replaceWith("<a id=\"next_button\" class=\"btn btn-primary pull-right\" href=\"<?php echo base_url().'view/exercise/';?>" + data['id_next'] + "\">Tovább&nbsp;<span class=\"glyphicon glyphicon-chevron-right\"></span></button>");
						}
						// Update results
						$.each($('.results'),function( index, value ) {
							if (index == 0) {
								$(this).text(data['results']['trophies']);
							} else if (index == 1) {
								$(this).text(data['results']['shields']);
							} else if (index == 2) {
								$(this).text(data['results']['points']);
							}
						})
						break;
					case 'WRONG':
						if (data['explanation'] != null) {
							$("#exercise_explanation").replaceWith('<div class="alert alert-warning text-left">' + data['explanation'] + '</div>');
							MathJax.Hub.Queue(["Typeset",MathJax.Hub,"hint"]);
						}
						$("#message").replaceWith('<div class="alert alert-danger"><strong><span class=\"glyphicon glyphicon-remove\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>');
						$("#next_button").replaceWith("<a id=\"next_button\" class=\"btn btn-primary pull-right\" href=\"<?php echo base_url();?>view/exercise/<?php echo $id;?>\">Újra&nbsp;<span class=\"glyphicon glyphicon-refresh\"></span></button>");
						if (data['submessages'].length > 0) {
							for (var i = data['submessages'].length - 1; i >= 0; i--) {
								var submessage = data['submessages'][i];
								if (submessage == 'CORRECT') {
									$('#input'+i).before('<span class=\"glyphicon glyphicon-ok green\"></span>&nbsp;');
								} else {
									$('#input'+i).before('<span class=\"glyphicon glyphicon-remove red\"></span>&nbsp;');
								}
							}
						}
						MathJax.Hub.Queue(["Typeset",MathJax.Hub,"message"]);
						break;
				}
			}
		});
	}
</script>