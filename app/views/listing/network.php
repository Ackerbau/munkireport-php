<?php $this->view('partials/head'); ?>

<?php 
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Network_model;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">

		$(document).on('appReady', function(e, lang) {

				// Get modifiers from data attribute
				var myCols = [], // Colnames
					mySort = [], // Initial sort
					hideThese = [], // Hidden columns
					col = 0; // Column counter

				$('.table th').map(function(){

					  myCols.push({'mData' : $(this).data('colname')});

					  if($(this).data('sort'))
					  {
					  	mySort.push([col, $(this).data('sort')])
					  }

					  if($(this).data('hide'))
					  {
					  	hideThese.push(col);
					  }

					  col++
				});

			    oTable = $('.table').dataTable( {
			        "sAjaxSource": "<?php echo url('datatables/data'); ?>",
			        "aaSorting": mySort,
			        "aoColumns": myCols,
			        "aoColumnDefs": [
			        	{ 'bVisible': false, "aTargets": hideThese }
					],
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?php echo url(); ?>/', '#tab_network-tab');
			        	$('td:eq(0)', nRow).html(link);

			        	// Status
			        	var status=$('td:eq(4)', nRow).html();
			        	status = status == 1 ? '<span class="label label-success">Enabled</span>' : 
			        		(status === '0' ? '<span class="label label-danger">Disabled</span>' : '')
			        	$('td:eq(4)', nRow).html(status)

				    }
			    } );
			} );
		</script>

		  <h3>Network report <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine#computer_name'>Name</th>
		        <th data-i18n="serial" data-colname='machine#serial_number'>Serial</th>
		        <th data-i18n="listing.username" data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='network#service'>Service</th>
		        <th data-colname='network#status'>Status</th>
		        <th data-colname='network#ethernet'>Ethernet</th>
		        <th data-colname='network#ipv4ip'>IP Address</th>
		        <th data-colname='network#ipv4router'>Router</th>
		        <th data-colname='network#ipv4mask'>Mask</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="6" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?php $this->view('partials/foot'); ?>