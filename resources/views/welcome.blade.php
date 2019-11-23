@extends('landing.app')

@section('title')
VCC
@endsection
@section('content')
  @if (App::isLocale('ko'))
  <section class="section1">
    <div class="content_section_page">
      <div class="left">
        <img class="orderbook_image" src="/images/landing/landing_orderbook.png?v=1" alt="">
      </div>
      <div class="right">
        <div class="text_notes_guide">
          <img class="bitpoint1" src="/images/landing/bitpoint1.png">
          <div class="list">
            <h3>
              <span class="number">1</span>
              <span class="content">@lang('landing.bitpoint1_pros1')</span>
            </h3>
            <h3>
              <span class="number">2</span>
              <span class="content">@lang('landing.bitpoint1_pros2')</span>
            </h3>
            <h3>
              <span class="number">3</span>
              <span class="content">@lang('landing.bitpoint1_pros3')</span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="section2">
    <div class="content_section_page">
      <div class="left">
        <div class="text_notes_guide">
          <img class="bitpoint2" src="/images/landing/bitpoint2.png">
          <div class="list">
            <h3>
              <span class="number">1</span>
              <span class="content">@lang('landing.bitpoint2_pros1')</span>
            </h3>
            <h3>
              <span class="number">2</span>
              <span class="content">@lang('landing.bitpoint2_pros2')</span>
            </h3>
            <h3>
              <span class="number">3</span>
              <span class="content">@lang('landing.bitpoint2_pros3')</span>
            </h3>
          </div>
        </div>
      </div>
      <div class="right">
        <img class="computer_image" src="/images/landing/landing_computer.png?v=1" alt="">
      </div>
    </div>
  </section>
  <section class="section3">
    <div class="content_section_page">
      <div class="text_notes_guide">
        <img class="bitpoint3" src="/images/landing/bitpoint3.png">
      </div>
    </div>
  </section>
  @else
    <section class="section1">
      <div class="content_section_page">
        <div class="left">
          <img class="orderbook_image" src="/images/landing/landing_orderbook.png?v=1" alt="">
        </div>
        <div class="right">
          <div class="text_notes_guide">
            <h2>
              <span class="prefix_header_title">@lang('Bit Point 1.')</span>
              <span class="header_title">@lang('Trading is easy.')</span>
            </h2>
            <p class="content_notes_guide">
              @lang('landing.bitpoint1_description')
            </p>
            <div class="list">
              <h3>
                <span class="number">1</span>
                <span class="content">@lang('landing.bitpoint1_pros1')</span>
              </h3>
              <h3>
                <span class="number">2</span>
                <span class="content">@lang('landing.bitpoint1_pros2')</span>
              </h3>
              <h3>
                <span class="number">3</span>
                <span class="content">@lang('landing.bitpoint1_pros3')</span>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section2">
      <div class="content_section_page">
        <div class="left">
          <div class="text_notes_guide">
            <h2>
              <span class="prefix_header_title">@lang('Bit Point 2.')</span>
              <span class="header_title">@lang('Trading is really easy.')</span>
            </h2>
            <p class="content_notes_guide">
              @lang('landing.bitpoint2_description')
            </p>
            <div class="list">
              <h3>
                <span class="number">1</span>
                <span class="content">@lang('landing.bitpoint2_pros1')</span>
              </h3>
              <h3>
                <span class="number">2</span>
                <span class="content">@lang('landing.bitpoint2_pros2')</span>
              </h3>
              <h3>
                <span class="number">3</span>
                <span class="content">@lang('landing.bitpoint2_pros3')</span>
              </h3>
            </div>
          </div>
        </div>
        <div class="right">
          <img class="computer_image" src="/images/landing/landing_computer.png?v=1" alt="">
        </div>
      </div>
    </section>
    <section class="section3">
      <div class="content_section_page">
        <div class="text_notes_guide">
          <h2>
            <span class="prefix_header_title">@lang('Bit Point 3.')</span>
            <span class="header_title">@lang('landing.bitpoint3_title')</span>
          </h2>
          <p class="content_notes_guide">
            @lang('landing.bitpoint3_description')
          </p>
        </div>
        <div class="boxs">
          <div class="left_box">
            <h2 class="box_title">@lang('WALLET & SYSTEM SECURITY')</h2>
            <div class="box_content">
              <div class="box_section">
                <h3 class="title_section">@lang('1. Minimize the amount of HOT-WALLET')</h3>
                <p class="content_section">@lang('⇒ Average 85% or more separate coin storage in WALLET')</p>
              </div>
              <div class="box_section">
                <h3 class="title_section">@lang('2. Limit holdings per wallet')</h3>
                <p class="content_section">@lang('⇒ Store in multiple wallets')</p>
              </div>
              <div class="box_section">
                <h3 class="title_section">@lang('3. Using MULTI-SIG wallet')</h3>
              </div>
            </div>
          </div>
          <div class="right_box">
            <h2 class="box_title">@lang('ACCOUNT & HUMAN SECURITY')</h2>
            <div class="box_content">
              <div class="box_section">
                <h3 class="title_section">@lang('landing.bitpoin3_pros1_title')</h3>
                <p class="content_section">@lang('landing.bitpoin3_pros1_detail')</p>
              </div>
              <div class="box_section">
                <h3 class="title_section">@lang('landing.bitpoin3_pros2_title')</h3>
                <p class="content_section">@lang('landing.bitpoin3_pros2_detail')</p>
              </div>
              <div class="box_section">
                <h3 class="title_section">@lang('landing.bitpoin3_pros3_title')</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <section class="section4">
    <div class="content_section_page">
      <div class="list-logo">
        <div class="bitgo">
          <img src="/images/landing/bitgo.png">
        </div>
        <div class="comodo">
          <img src="/images/landing/comodo.png">
        </div>
        <div class="nshc">
          <img src="/images/landing/nshc.png">
        </div>
        <div class="google_authen">
          <img src="/images/landing/google_authen.png">
          <div class="text">@lang('Google Authenticator')</div>
        </div>
      </div>
    </div>
  </section>
@endsection
