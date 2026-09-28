<?php
/**
 * Template Name: Guide: Property Taxes by County
 *
 * REBUILT 2026-09-28 from "Amber Randhawa - Full Website Content (v2) DUTCH"
 * as edited by Amber. The old page was built on the claim that Polk County's
 * whole tax bill is uniquely capped. Amber's fact-check: "there is nothing
 * unique or different about Polk County property taxes as compared to the
 * state of Georgia as a whole." That claim, the opt-out table, the effective
 * rate table and every county-by-county number are OUT until re-verified
 * against the Georgia Secretary of State filing list. The previous version
 * is page-guide-taxes.php.bak-amberedit-20260928.
 *
 * What remains is built only from Amber's own blog post.
 *
 * @package amber-randhawa
 */

get_header();

$trail = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Guides', 'url' => home_url( '/guides/' ) ],
    [ 'label' => 'Property taxes' ],
];
?>

<main class="lr" id="main">

  <?php $ar_band = get_template_directory() . '/assets/images/band-property-taxes.jpg'; ?>
  <header class="lr-head<?php echo file_exists( $ar_band ) ? ' lr-head--photo has-photo' : ''; ?>">
    <?php get_template_part( 'template-parts/head-band', null, [ 'slug' => 'property-taxes' ] ); ?>

    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp">Property taxes out here, in plain English</h1>
      <span class="lr-mark" aria-hidden="true"></span>
    </div>
  </header>

  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-doc">
      <div class="lr-doc-main lr-rise">
        <p>If you&rsquo;ve been frustrated watching your property tax bill creep up year after year, I&rsquo;ve got some genuinely good news for you, and I <a href="<?php echo esc_url( home_url( '/big-changes-to-georgia-property-taxes/' ) ); ?>">wrote the whole thing up on my blog</a>. The short version:</p>
        <p>Georgia now caps how fast your homesteaded property&rsquo;s taxable value can grow, at the rate of inflation. The original version let local governments opt out, and a lot of them did, including around two thirds of Georgia&rsquo;s school systems, so plenty of folks never got the protection they thought they voted for. That loophole is now closed. The cap applies everywhere, no exceptions, no opt-outs.</p>
        <p>A few more things the new laws do:</p>
        <ul class="lr-list">
          <li>Counties and some cities can levy a new 1% local sales tax to offset homeowner property tax bills, starting January 1, 2028.</li>
          <li>If the county applies your homestead exemption wrong through no fault of yours, they can&rsquo;t come back years later and bill you for their mistake. If the county made the error, the county eats the cost. Period.</li>
          <li>Beginning in 2027, local governments and school boards that want budgets projecting property tax revenue growth past a set threshold need voter approval first.</li>
          <li>The Homeowner Tax Relief Grant applies relief directly to your bill. No application required.</li>
        </ul>
        <p>What the cap does NOT do is freeze your millage rate. Your assessed value is held to inflation, and then the county multiplies that value by whatever rate it adopts. A board that wants more revenue can raise the rate, hold a hearing, and take it. And when a county announces a &ldquo;full rollback,&rdquo; read your assessment notice, not the press release. The rate coming down on paper and your bill going down are two different things.</p>
        <p>Every county out here sets its own millage rate, and your total rate depends on whether your address is incorporated or unincorporated, which school district serves it, and which exemptions you qualify for. That is why the only honest answer to &ldquo;what would my taxes be&rdquo; is a specific answer about a specific house.</p>
        <p class="lr-note"><i>I&rsquo;m a real estate agent, not a tax attorney or financial advisor. For advice specific to your situation, talk to a tax professional or your county tax commissioner&rsquo;s office.</i></p>
      </div>

      <aside class="lr-doc-aside lr-rise">
        <div class="lr-aside-block">
          <?php get_template_part( 'template-parts/slot', null, [
              'file' => 'guide-taxes.jpg', 'label' => 'A county courthouse', 'alt' => 'County courthouse, northwest Georgia',
          ] ); ?>
        </div>
        <?php if ( function_exists( 'ar_render_page_posts' ) ) { ar_render_page_posts( 'property-taxes' ); } ?>
        <div class="lr-aside-block">
          <span class="lr-aside-h">Next</span>
          <ul class="lr-aside-list">
            <li><a href="<?php echo esc_url( home_url( '/commuting-to-atlanta/' ) ); ?>">How long is the drive to Atlanta?</a></li>
            <li><a href="<?php echo esc_url( home_url( '/communities/' ) ); ?>">All the towns I work</a></li>
          </ul>
        </div>
      </aside>
    </div>
  </section>

  <?php get_template_part( 'template-parts/close', null, [
      'heading' => 'So ask me what the bill will actually be.',
      'body'    => 'Not the rate, the bill. Give me an address and a price and I will walk you through what you would really pay, which line of it is capped, and which line isn\'t.',
  ] ); ?>

</main>

<?php get_footer(); ?>
