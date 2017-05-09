<?php get_header(); ?>

<section class="content">

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
  <?php /*hu_get_template_part('parts/page-title');*/ ?>
  <div class="page-title pad group">
    <h2>Ausgewähltes Seminar</h2>
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
              ?>
              <div class="event-dates">
                Von <?php echo $event_fromdate; ?>
                bis <?php echo $event_todate; ?>
              </div>
              <br>

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
                <div id="event-infos">
                  <h2>Informationen zum Seminar</h2>
                  <?php $current_infos = get_post_meta($post->ID, 'current_infos', true);
                        $arrival       = get_post_meta($post->ID, 'arrival', true);
                        $departure     = get_post_meta($post->ID, 'departure', true);
                        $costs_participation = get_post_meta($post->ID, 'costs_participation', true);
                        $costs_catering      = get_post_meta($post->ID, 'costs_catering', true);
                        $info_housing        = get_post_meta($post->ID, 'info_housing', true);
                        $participants_please_bring   = get_post_meta($post->ID, 'participants_please_bring', true);
                        $participants_prerequisites  = get_post_meta($post->ID, 'participants_prerequisites', true);
                        ?>
                  <?php if(!empty($arrival)) { ?>
                  <div id="arrival-info">
                    <h3>Anreise</h3>
                    <?php echo $arrival; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($departure)) { ?>
                  <div id="departure-info">
                    <h3>Abreise</h3>
                    <?php echo $departure; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($costs_participation)) { ?>
                  <div id="costs-participation">
                    <h3>Seminarkosten</h3>
                    <?php echo $costs_participation; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($costs_catering)) { ?>
                  <div id="costs-catering">
                    <h3>Biovollverpflegung</h3>
                    <?php echo $costs_catering; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($info_housing)) { ?>
                  <div id="info-housing">
                    <h3>Unterkunft</h3>
                    <?php echo $info_housing; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($current_infos)) { ?>
                  <div id="current-info">
                    <h3>Aktuelle Informationen</h3>
                    <?php echo $current_infos; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($participants_please_bring)) { ?>
                  <div id="participants-please-bring">
                    <h3>Bitte mitbringen</h3>
                    <?php echo $participants_please_bring; ?>
                  </div>
                  <?php } ?>
                  <?php if(!empty($participants_prerequisites)) { ?>
                  <div id="participants-prerequisites">
                    <h3>Voraussetzungen für Teilnehmer*</h3>
                    <?php echo $participants_prerequisites; ?>
                  </div>
                  <?php } ?>
                </div> <!-- #event-infos -->

                <?php
                $referees = referees_by_event($post->ID);
                if ( $referees-> have_posts() ) { ?>
                  <div id="referees">
                  <h2>Referent*</h2>
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
                  $todaytotime = strtotime('today');
                  if(get_post_meta($event->ID, 'registration_needed', true) && ev7l_is_after($event->ID, $todaytotime)) { ?>

                <div id="registration">
                  <h2>Anmeldung</h2>
                  <span style="color:red;">
                    ACHTUNG: siebenlinden.org ist noch ganz frisch.  Solltest du andere Personen mit anmelden wollen, benutze bitte vorerst unsere 'alte' Webseite:
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

                    <br/>
                    <br class="clear"/>
                    <div class="registration-controls">
                    <h4>Rücktrittsbedingungen</h4>
                    <?php echo get_post_meta($post->ID, 'cancel_conditions', true); ?><br/>
                    <br/>
                    <input type="checkbox" id="accept_terms" name="registration[accept_terms]">Ich akzeptiere die Rücktrittsbedingungen und die <a href="/seminare/agb/">Allgemeinen Geschäftsbedingungen</a></input>
                    <br/>
                    <br/>
                    <span style="color:red;">
                      ACHTUNG, zur Zeit wirst Du nach/zur Anmeldung noch auf das alte Portal (http://seminare.siebenlinden.de) weitergeleitet.
                    </span>
                    <br/>

                    <input type="submit" value="Anmelden"></submit>
                    </div>
                  </form>
                </div> <!-- #registration -->
                <hr/>
                <div id="question">
                  <h3>Frage zur Veranstaltung stellen</h3>
                  <?php echo do_shortcode('[contact-form-7 id="2325" title="Frage zu Veranstaltung"]'); ?>
                </div>
                <script type="text/javascript">
                    document.getElementsByClassName("wpcf7-form")[0].elements['your-subject'].value = 'Frage zu Veranstaltung: <?php echo the_title(); ?> (<?php echo $event_fromdate.' - '.$event_todate; ?>)';
                </script>
              <?php
                }
              elseif (ev7l_is_after($event->ID, $todaytotime)) { ?>
                <b>Keine Anmeldung nötig.</b>
              <?php
                } else { ?>
                <b>Veranstaltung liegt in der Vergangenheit.</b>
              <?php
                }
               ?>

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
