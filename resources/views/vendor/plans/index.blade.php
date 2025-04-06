@extends('vendor/layouts/master')

@section('title')
@endsection
@section('page_name')
@endsection
@section('content')

<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css"
      integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"
      integrity="sha512-sH8JPhKJUeA9PWk3eOcOl8U+lfZTgtBXD41q6cO/slwxGHCxKcW45K4oPCUhHG7NMB4mbKEddVmPuTXtpbCbFA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
<style>
    p{
       color: black;
    }
      .pricing .price-table {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
      }
      @media screen and (max-width: 991.98px) {
        .pricing .price-table {
          max-width: 370px;
          margin: 0 auto;
        }
      }
      .pricing .price-table .pricing-panel {
        background-color: white !important;
        padding: 44px 50px 42px;
        border-radius: 8px;
        -webkit-box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11);
        box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
      }
      @media (min-width: 992px) and (max-width: 1200px) {
        .pricing .price-table .pricing-panel {
          padding-right: 25px;
          padding-left: 25px;
        }
      }
      @media screen and (max-width: 991.98px) {
        .pricing .price-table .pricing-panel {
          text-align: center;
          max-width: 370px;
          margin: 0 auto 30px;
        }
      }
      @media screen and (max-width: 767.98px) {
        .pricing .price-table .pricing-panel {
          padding-left: 25px;
          padding-right: 25px;
        }
      }
      .pricing
        .price-table
        .pricing-panel
        .pricing-body
        .pricing-heading
        .pricing-title {
        font-size: 18px;
        font-weight: 700px;
        line-height: 26px;
        color: black !important;
        margin-bottom: 0;
        position: relative;
        text-transform: capitalize;
        -webkit-transition: 300ms ease-in-out;
        -o-transition: 300ms ease-in-out;
        transition: 300ms ease-in-out;
      }
      .pricing
        .price-table
        .pricing-panel
        .pricing-body
        .pricing-heading
        .pricing-desc {
        font-size: 14px;
        font-weight: 400;
        color: #9b9b9b !important;
        line-height: 26px;
        margin-bottom: 0;
      }
      .pricing .price-table .pricing-panel .pricing-body .pricing-price {
        margin-bottom: 34px;
      }
      .pricing .price-table .pricing-panel .pricing-body .pricing-price p {
        padding-top: 55px;
        margin-bottom: 29px;
        font-weight: 400;
        color: #9b9b9b !important;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: baseline;
        -ms-flex-align: baseline;
        align-items: baseline;
        line-height: 30px;
        position: relative;
      }
      .advantages-list {
        text-align: left;
        margin: 0;
        padding: 0;
        list-style: none;
      }
      .advantages-list li {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        font-weight: 700;
        font-size: 15px;
        line-height: 36px;
        color: black !important;
        text-transform: capitalize;
      }
      @media (min-width: 992px) and (max-width: 1200px) {
        .advantages-list li {
          font-size: 14px;
        }
      }
      @media screen and (max-width: 991.98px) {
        .advantages-list li {
          display: -webkit-inline-box;
          display: -ms-inline-flexbox;
          display: inline-flex;
          margin-right: 20px;
        }
      }
      @media screen and (max-width: 767.98px) {
        .advantages-list li {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          margin-right: 0;
        }
      }
      .advantages-list li::before {
        content: "";
        font-weight: 700;
        width: 10px;
        height: 10px;
        display: -webkit-inline-box;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        background-color: blue !important;
        color: white !important;
        margin-left: 15px;
        border-radius: 50%;
        font-size: 10px;
      }
      @media (min-width: 992px) and (max-width: 1200px) {
        .advantages-list li::before {
          margin-left: 8px;
        }
      }

      .pricing .price-table .pricing-panel .pricing-body .pricing-price p {
        padding-top: 55px;
        margin-bottom: 29px;
        font-weight: 400;
        color: black !important;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: baseline;
        -ms-flex-align: baseline;
        align-items: baseline;
        line-height: 30px;
        position: relative;
      }
      .pricing
        .price-table
        .pricing-panel
        .pricing-body
        .pricing-price
        p
        .currency {
        font-size: 50px;
      }
    </style>

<section class="pricing pricing-1" id="pricing-1">
      <div class="container">
        <form>
          <div class="row">
            <div class="col-12 col-lg-4 d-flex">
              <div class="price-table">
                <div class="pricing-panel">
                  <div class="pricing-body">
                    <div class="pricing-heading">
                      <h4 class="pricing-title">Starter Plan</h4>
                    </div>
                    <div class="pricing-price">
                      <p>
                        <span class="currency">$50</span
                        ><span class="time"> Month</span>
                      </p>
                    </div>
                    <div class="pricing-list">
                      <p>
                        Fast project turnaround time, substantial cost savings
                        &amp; quality standards.
                      </p>
                      <ul class="advantages-list list-unstyled">
                        <li>Reliability and performance</li>
                        <li>Just-in-time manufacturing</li>
                        <li>Solar material financing</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4 d-flex">
              <div class="price-table">
                <div class="pricing-panel active">
                  <div class="pricing-body">
                    <div class="pricing-heading">
                      <h4 class="pricing-title">basic Plan</h4>
                    </div>
                    <div class="pricing-price">
                      <p>
                        <span class="currency">$70</span
                        ><span class="time"> Month</span>
                      </p>
                    </div>
                    <div class="pricing-list">
                      <p>
                        Fast project turnaround time, substantial cost savings
                        &amp; quality standards.
                      </p>
                      <ul class="advantages-list list-unstyled">
                        <li>Built using n-type mono</li>
                        <li>Crystalline cell materials</li>
                        <li>Reliability and performance</li>
                        <li>Just-in-time manufacturing</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4 d-flex">
              <div class="price-table">
                <div class="pricing-panel">
                  <div class="pricing-body">
                    <div class="pricing-heading">
                      <h4 class="pricing-title">advanced Plan</h4>
                    </div>
                    <div class="pricing-price">
                      <p>
                        <span class="currency">$90</span
                        ><span class="time"> Month</span>
                      </p>
                    </div>
                    <div class="pricing-list">
                      <p>
                        Fast project turnaround time, substantial cost savings
                        &amp; quality standards.
                      </p>
                      <ul class="advantages-list list-unstyled">
                        <li>Built using n-type mono</li>
                        <li>Crystalline cell materials</li>
                        <li>Reliability and performance</li>
                        <li>Just-in-time manufacturing</li>
                        <li>Solar material financing</li>
                        <li>50% more energy output</li>
                      </ul>
                    </div>
                  </div>
                   <!-- Button trigger modal -->
                <button
                type="button"
                class="mt-4 btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
              >
                Launch demo modal
              </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>

    <!-- Modal -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">...</div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    @include('vendor/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
   
@endsection
