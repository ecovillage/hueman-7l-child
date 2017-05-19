<?php get_header(); ?>

<section class="content">

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
  <?php /*hu_get_template_part('parts/page-title');*/ ?>
  <div class="page-title pad group">
  <h2><?php echo __('Ausgewähltes Seminar', 'hueman-7l-child'); ?></h2>
  </div><!--/.page-title-->
<?php /* Re-entering vanilla hueman */ ?>

	<div class="pad group">

		<?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?>>
				<div class="post-inner group">

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
<?php /* <?php hu_get_template_part('parts/page-image'); ?> */ ?>
<?php /* Re-entering vanilla hueman */ ?>

          <?php hu_get_template_part('parts/single-heading'); ?>

					<?php if( get_post_format() ) { get_template_part('parts/post-formats'); } ?>

					<div class="clear"></div>

					<div class="<?php echo implode( ' ', apply_filters( 'hu_single_entry_class', array('entry','themeform') ) ) ?>">
            <div class="entry-inner">
             <h1><?php echo the_title(); ?></h1>

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
              <?php hu_get_template_part('parts/page-image'); ?>
              <?php
                $event = $post;
                $event_fromdate = date_i18n('d.M. Y', get_post_meta($post->ID, 'fromdate', true));
                $event_todate   = date_i18n('d.M. Y', get_post_meta($post->ID, 'todate', true));
                $today_date     = strtotime('today');
                $event_needs_registration = get_post_meta($event->ID, 'registration_needed', true) == 'true';
                $event_is_future = ev7l_is_after($event->ID, $today_date);
              ?>
              <div class="event-dates">
                <?php echo __('Von', 'hueman-7l-child');  echo $event_fromdate; ?>
                <?php echo __('bis', 'hueman-7l-child');  echo $event_todate; ?>
              </div>
              <br>

              <?php /* Or: set_query_var to pass over localish variables. */ ?>
              <a name="description"/>
              <?php include(locate_template('parts/single-ev7l-event-nav.php')); ?>
              </br>

              <?php if(false && get_post_meta($post->ID, 'event_category_id', false)) {
                ?>
                In Rubriken:
                <?php
                $ev_cats = new WP_Query( array(
                  'post_type' => 'ev7l-event-category',
                  'post__in' => get_post_meta( $post->ID, 'event_category_id', false),
                  'nopaging' => true) );
                if ( $ev_cats-> have_posts() ) {
                  while ( $ev_cats->have_posts() ) {
                    $ev_cats->the_post();
                    ?>
                    <a class="event-category-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              <?php
                  }}
              ?><br/><?php
              } ?>

                <?php wp_reset_postdata(); ?>

              <?php the_content(); ?>
                <hr/>
                <?php include(locate_template('parts/single-ev7l-event-nav.php')); ?>
                <a name="informations"/>
                <div id="event-infos">
                <h2><?php echo __('Informationen zum Seminar', 'hueman-7l-child'); ?></h2>
                  <?php $current_infos = get_post_meta($post->ID, 'current_infos', true);
                        $arrival       = get_post_meta($post->ID, 'arrival', true);
                        $departure     = get_post_meta($post->ID, 'departure', true);
                        $costs_participation = get_post_meta($post->ID, 'costs_participation', true);
                        $costs_catering      = get_post_meta($post->ID, 'costs_catering', true);
                        $info_housing        = get_post_meta($post->ID, 'info_housing', true);
                        $other_infos         = get_post_meta($post->ID, 'other_infos', true);
                        $participants_please_bring   = get_post_meta($post->ID, 'participants_please_bring', true);
                        $participants_prerequisites  = get_post_meta($post->ID, 'participants_prerequisites', true);
                        ?>
                  <?php if(!empty($current_infos) && trim($current_infos) != '') { ?>
                  <div id="current-info">
                    <h3><?php echo __('Aktuelle Informationen', 'hueman-7l-child'); ?></h3>
                    <?php echo $current_infos; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($arrival)) { ?>
                  <div id="arrival-info">
                  <h3><?php echo __('Anreise', 'hueman-7l-child'); ?></h3>
                    <?php echo $arrival; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($departure)) { ?>
                  <div id="departure-info">
                    <h3><?php echo __('Abreise', 'hueman-7l-child'); ?></h3>
                    <?php echo $departure; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($costs_participation)) { ?>
                  <div id="costs-participation">
                    <h3><?php echo __('Seminarkosten', 'hueman-7l-child'); ?></h3>
                    <?php echo $costs_participation; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($costs_catering)) { ?>
                  <div id="costs-catering">
                    <h3><?php echo __('Biovollverpflegung', 'hueman-7l-child'); ?></h3>
                    <?php echo $costs_catering; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($info_housing)) { ?>
                  <div id="info-housing">
                    <h3><?php echo __('Unterkunft', 'hueman-7l-child'); ?></h3>
                    <?php echo $info_housing; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($participants_please_bring)) { ?>
                  <div id="participants-please-bring">
                    <h3><?php echo __('Bitte mitbringen', 'hueman-7l-child'); ?></h3>
                    <?php echo $participants_please_bring; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($participants_prerequisites)) { ?>
                  <div id="participants-prerequisites">
                    <h3><?php echo __('Voraussetzungen für Teilnehmer*', 'hueman-7l-child'); ?></h3>
                    <?php echo $participants_prerequisites; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($other_infos)) {
                    echo $other_infos;
                  } ?>
                </div> <!-- #event-infos -->

                <?php
                $referees = referees_by_event($post->ID);
                if ( $referees-> have_posts() ) { ?>
                  <hr/>
                  <div id="referees">
                  <h2><?php echo __('Referent*', 'hueman-7l-child'); ?></h2>
                  <?php
                  while ( $referees->have_posts() ) {
                    $referees->the_post();
                    ?>
                    <?php if ( has_post_thumbnail() ): ?>
                      <div class="grid two-third">
                        <h3><a class="referee-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="referee-qualification">
                          <?php echo get_post_meta($event->ID, 'referee_'.$post->ID.'_qualification', true); ?>
                        </div>
                      </div>
                      <div class="grid one-third last">
                        <div class="referee-image">
                          <?php hu_the_post_thumbnail('thumbnail'); ?>
                        </div>
                      </div>
                    <?php else: ?>
                      <a class="referee-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      <div class="referee-qualification">
                        <?php echo get_post_meta($event->ID, 'referee_'.$post->ID.'_qualification', true); ?>
                      </div>
                    <?php endif; /* TODO: image-grid, placeholder would be cool */ ?>

                <!-- Only if correct time and the seminar actually needs registration ... -->


              <?php
                  }
                echo "</div>";
                }
                wp_reset_postdata();
              ?>
              <br/>
                <?php
                  if($event_needs_registration && $event_is_future) { ?>

                <?php include(locate_template('parts/single-ev7l-event-nav.php')); ?>
                <a name="registration"/>
                <div id="registration">
                  <hr/>
                  <h2><?php echo __('Anmeldung', 'hueman-7l-child'); ?></h2>

                  <?php if($event_is_future && !empty($current_infos)) { ?>
                  <div id="current-info">
                    <?php echo $current_infos; ?>
                  </div>
                  <?php } ?>

                  <span style="color:red;">
                  	<?php echo  __('ACHTUNG: siebenlinden.org ist noch ganz frisch.  Solltest du andere Personen mit anmelden wollen, benutze bitte vorerst unsere 'alte' Webseite:', 'hueman-7l-child'); ?>
                  </span>
                  <a href="http://seminare.siebenlinden.de/seminar/<?php echo get_post_meta($event->ID, 'uuid', true); ?>"> Anmeldungen über alte Webseite hier möglich.</a>
                  <form id="registration_form" action="http://seminare.siebenlinden.de/seminar/register" charset="UTF-8" method="POST">
                    <input type="hidden" name="seminar_id" value="<?php echo get_post_meta($event->ID, 'uuid', true) ?>"/>

                    <div class="grid one-half">
                      <label for="firstname">Vorname</label>
                      <input type="text" placeholder="Vorname" id="firstname" name="registration[firstname]"/>

                      <label for="lastname">Nachname</label>
                      <input type="text" placeholder="Nachname" id="lastname" name="registration[lastname]"/>

                      <label for="street_and_no">Straße und Hausnummer</label>
                      <input type="text" placeholder="Straße und Hausnummer" id="street_and_no" name="registration[street_and_no]"/>

                      <label for="zip">PLZ</label>
                      <input type="text" placeholder="PLZ" id="zip" name="registration[zip]"/>

                      <label for="city">Ort</label>
                      <input type="text" placeholder="Ort" id="city" name="registration[city]"/>

                      <label for="land">Land</label>
                      <input type="text" placeholder="Land" id="land" name="registration[land]"/>
                    </div>

                    <div class="grid one-half last">
                      <label for="email">E-Mail</label>
                      <input type="text" placeholder="email" id="email" name="registration[email]"/>

                      <label for="phone">Telefonnummer</label>
                      <input type="text" placeholder="0123 45678" id="phone" name="registration[phone]"/>

                      <label for="mobile">Handy-Nummer</label>
                      <input type="text" placeholder="0123 45678" id="mobile" name="registration[mobile]"/>

                      <label for="comment">Bemerkung</label>
                      <textarea placeholder="..." id="comment" name="registration[comment]" rows="7"></textarea>
                    </div>
                    <br class="clear"/>
                    <br/>
                    <h5>Übernachtung: Raumwünsche</h5>
                    <div class="grid one-half last">
                      <input type="checkbox" name="room_wish[]" id="4_Bett_Zimmer" value="4-Bett-Zimmer"/>
                      <label for="4_Bett_Zimmer">4-Bett-Zimmer</label>
                      <br/>

                      <input id='2_Bett_Zimmer' name='room_wish[]' type='checkbox' value='2-Bett-Zimmer'/>
                      <label for='2_Bett_Zimmer'>2-Bett-Zimmer</label>
                      <br/>

                      <input id='Einzelzimmer' name='room_wish[]' type='checkbox' value='Einzelzimmer'/>
                      <label for='Einzelzimmer'>Einzelzimmer</label>
                      <br/>

                      <input id='H_tte' name='room_wish[]' type='checkbox' value='Hütte'/>
                      <label for='H_tte'>Hütte</label>
                    </div>

                    <div class="grid one-half last">
                      <input id='Eigenes_Zelt' name='room_wish[]' type='checkbox' value='Eigenes Zelt'/>
                      <label for='Eigenes_Zelt'>Eigenes Zelt</label>
                      <br/>

                      <input id='Eigenes_Wohnmobil_wagen' name='room_wish[]' type='checkbox' value='Eigenes Wohnmobil/-wagen'/>
                      <label for='Eigenes_Wohnmobil_wagen'>Eigenes Wohnmobil/-wagen</label>
                      <br/>

                      <input id='Privat_Selbstorganisiert' name='room_wish[]' type='checkbox' value='Privat / Selbstorganisiert'/>
                      <label for='Privat_Selbstorganisiert'>Privat / Selbstorganisiert</label>
                    </div>
                    <br class="clear"/>


                    <br/>
                    <br class="clear"/>
                    <br/>
                    <div class="registration-controls">
                    <h4><?php echo __('Rücktrittsbedingungen', 'hueman-7l-child'); ?></h4>
                    <span class="cancel_conditions"><?php echo get_post_meta($post->ID, 'cancel_conditions', true); ?></span><br/>
                    <br/>
                    <input type="checkbox" id="accept_terms" name="registration[accept_terms]">Ich akzeptiere die Rücktrittsbedingungen und die <a href="/seminare/agb/">Allgemeinen Geschäftsbedingungen</a></input>
                    <br/>
                    <br/>
                    <span style="color:red;">
                      <?php echo __('ACHTUNG, zur Zeit wirst Du nach/zur Anmeldung noch auf das alte Portal (http://seminare.siebenlinden.de) weitergeleitet.', 'hueman-7l-child'); ?>
                    </span>
                    <br/>
                    <br/>

                    <input type="submit" value="Anmelden"></submit>
                    </div>
                  </form>
                </div> <!-- #registration -->
              <?php
                }
              elseif ($event_is_future) { ?>
                <b><?php echo __('Keine Anmeldung nötig.', 'hueman-7l-child'); ?></b>
              <?php
                } else { ?>
                <b><?php echo __('Veranstaltung liegt in der Vergangenheit.', 'heuman-7l-child'); ?></b>
              <?php
                }
?>
              <hr/>
              <?php include(locate_template('parts/single-ev7l-event-nav.php')); ?>
              <a name="question"/>
              <div id="question">
                <h3><?php echo __('Frage zur Veranstaltung stellen', 'hueman-7l-child'); ?> </h3>
                <?php echo do_shortcode('[contact-form-7 id="2325" title="Frage zu Veranstaltung"]'); ?>
              </div>
              <script type="text/javascript">
                  document.getElementsByClassName("wpcf7-form")[0].elements['your-subject'].value = 'Frage zu Veranstaltung: <?php echo the_title(); ?> (<?php echo $event_fromdate.' - '.$event_todate; ?>)';
              </script>

<?php /* Re-entering vanilla hueman */ ?>
							<nav class="pagination group">
                <?php
                  //Checks for and uses wp_pagenavi to display page navigation for multi-page posts.
                  if ( function_exists('wp_pagenavi') )
                    wp_pagenavi( array( 'type' => 'multipart' ) );
                  else
                    wp_link_pages(array('before'=>'<div class="post-pages">'.__('Pages:','hueman'),'after'=>'</div>'));
                ?>
              </nav><!--/.pagination-->
						</div>

            <?php do_action( 'hu_after_single_entry_inner' ); ?>

						<div class="clear"></div>
					</div><!--/.entry-->

				</div><!--/.post-inner-->
			</article><!--/.post-->
		<?php endwhile; ?>

		<div class="clear"></div>

		<?php the_tags('<p class="post-tags"><span>'.__('Tags:','hueman').'</span> ','','</p>'); ?>

		<?php if ( ( hu_is_checked( 'author-bio' ) ) && get_the_author_meta( 'description' ) ): ?>
			<div class="author-bio">
				<div class="bio-avatar"><?php echo get_avatar(get_the_author_meta('user_email'),'128'); ?></div>
				<p class="bio-name"><?php the_author_meta('display_name'); ?></p>
				<p class="bio-desc"><?php the_author_meta('description'); ?></p>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

		<?php if ( 'content' == hu_get_option( 'post-nav' ) ) { get_template_part('parts/post-nav'); } ?>

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
    <?php /*if ( '1' != hu_get_option( 'related-posts' ) ) { get_template_part('parts/related-posts'); } */?>
<?php /* Re-entering vanilla hueman */ ?>

		<?php if ( hu_is_checked('post-comments') ) { comments_template('/comments.php',true); } ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
