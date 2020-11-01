<?php
/**
 * Undocumented function
 *
 * @return void
 */
/**
 * Display chart shortcode
 *
 * @return chart
 */
function vgs_display_chart_shortcode() {

	// Collect the data to display.
	$args = array(
		'post_type'      => 'income',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'order'          => 'ASC',
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'year',
	);
	$posts = get_posts( $args );

	foreach ( $posts as $post ) {
		$years[] = get_field( 'year', $post->ID );
	}
	$years = array_unique( $years );

	$cur_year  = get_the_date( 'Y' );
	$cur_month = get_the_date( 'n' );
	$colors    = array(
		'rgb(238, 71, 91)',
		'rgb(72, 123, 253)',
		'rgb(74, 191, 133)',
		'rgb(242, 171, 20)',
		'rgb(167, 95, 12)',
		'rgb(244, 197, 25)',
		'rgb(116, 3, 58)',
		'rgb(131, 96, 136)',
		'rgb(191, 241, 42)',
		'rgb(164, 67, 160)',
	);

	foreach ( $years as $year ) {
		$args = array(
			'post_type'      => 'income',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'month',
			'meta_query'    => array(
				array(
					'key'     => 'year',
					'value'   => $year,
					'compare' => '=',
				),
			),
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$post_id     = $post->ID;
			$income_type = get_field( 'type', $post_id );

			switch ( $income_type ) {
				case 'recurring':
					$data[ $year ]['recurring'][ get_field( 'month', $post_id ) ] = get_field( 'income', $post_id );
					break;
				case 'invoice':
					$data[ $year ]['invoice'][ get_field( 'month', $post_id ) ] = get_field( 'income', $post_id );
					break;
			}
		}
	}

	// Work with $data array.
	// loop the years.
	foreach ( $data as $year => $value ) {
		// loop the types.
		foreach ( $value as $type => $v ) {
			// loop the months.
			foreach ( $v as $month => $income ) {
				$datarow[ $year . '_' . $type ][ $month ] = $income;
			}
		}
	}
	// make lines.
	foreach ( $datarow as $line => $vals ) {
		$row_count++;
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( ( $year <= $cur_year ) && ( $month <= $cur_month ) ) {
				$values[ $i ] = $vals[ $i ];
			}
		}

		$line_data              = explode( '_', $line );
		$dataset[ $row_count ]  = '{';
		$dataset[ $row_count ] .= "label: '" . $line_data[0] . " " . ucwords( $line_data[1] ) . "',";
		$dataset[ $row_count ] .= "backgroundColor: 'rgb(255, 255, 255)',";
		$dataset[ $row_count ] .= "borderColor: '" . $colors[ $row_count ] ."',";
		$dataset[ $row_count ] .= "data: [ " . implode( ', ', $values ) . "],";
		$dataset[ $row_count ] .= "fill: false";
		$dataset[ $row_count ] .= "}";
	}

	$datasets = implode( ',', $dataset );
	// Display our chart output.
	ob_start();
	?>
	<canvas id="myChart"></canvas>
	<script>
	var ctx = document.getElementById('myChart').getContext('2d');
	var chart = new Chart(ctx, {
		// The type of chart we want to create
		type: 'line',

		// The data for our dataset
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			datasets: [
				<?php echo $datasets; ?>
			]
		},

		// Configuration options go here
		options: {}
	});</script>

	<?php
	return ob_get_clean();
}
add_shortcode( 'chart', 'vgs_display_chart_shortcode' );
