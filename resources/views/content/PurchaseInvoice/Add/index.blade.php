@inject('PurchaseInvoice','App\Http\Controllers\PurchaseInvoiceController')
@extends('adminlte::page')

@section('title','MOZAIC Practice')
@section('js')
<script>
    function function_elements_add(name, value){
        $.ajax({
            type: "POST",
            url:  "{{ url('purchase-invoice/add-elements') }}",
            data: {
                'name'  :name,
                'value' :value,
                '_token':'{{csrf_token()}}'
            },
            success: function(msg){

            }
        });
    }

    $(document).ready(function(){
        $("quantity").change(function(){
            var quantity = $("#quantity").val();
            var cost = $("#item_unit_cost").val();
            var subtotal = quantity * cost;

            $("#subtotal_amount").val(subtotal);
            $("#subtotal_amount_view").val(toRp(subtotal));
            $("#subtotal_amount_after_discount_view").val(toRp(subtotal));
            $("#subtotal_amount_after_discount").val(subtotal);
        });
    

        $('#item_price_new_view').change(function(){
            var price_new = parseInt($('#item_price_new_view').val());
            var cost_new = parseInt($('#item_cost_view').val());
            var price_new = parseInt($('#margin_percentage_old').val());
            var price_new = parseInt($('#margin_percentage').val());
            var margin_percentage = ((price_new - cost_new) /  cost_new) * 100;

                if(Number.isInteger(margin_percentage)){
                    $('#margin_percentage').val(margin_percentage);
                } else{
                    $('#margin_percentage').val(margin_percentage.toFixed(2));
                }
                $('#item_price_new_view').val(toRp(price_new));
                $('#item_price_new').val(price_new);
        });

        $('#margin_percentage').change(function(){
            var cost_new = parseInt($('#item_cost_new').val());
            var margin_old = parseInt($('#margin_Percentage_old').val());
            var margin = parseInt($('#margin_percentage').val());
            var price_new = ((margin * cost_new) / 100) +cost_new;

                if(Number.isInteger(margin)){
                    $('#margin_percentage').val(margin);
                } else{
                    $('#margin_percentage').val(margin.toFixed(2));
                }
                $('#item_price_new_view').val(toRp(price_new));
                $('#item_price_new').val(price_new);
        });

        $("#item_unit_cost_view").change(function(){
            var item_package_id     = $("#item_package_id").val();
            var cost_new            = $("#item_unit_cost_view").val();
            var cost                = $("item_unit_cost").val();
            $.ajax({
                url::"{{route(PI.index) }}"+'/'+item_package_id,
                type: "GET",
                dataType: "html",
                success:function(price)
                {
                    if(price != '') {
                        if(cost != cost_new){
                            $.ajax({
                                url: "{{url('get-margin-category')}}"+'/'+item_package_id,
                                type: "GET",
                                dataType: "html",
                                success:function(margin)
                                {
                                    if(margin != ''){
                                        $('#margin_percentage').val(margin);
                                        $('#margin_percentage_old').val(margin);
                                        var price_new = parseInt(cost_new) + ((parseInt(cost_new) * margin) / 100);
                                        $('#item_price_new_view').val(toRp(price_new));
                                        $('#item_price_new').val(price_new);
                                    }
                                }
                            });
                            $('#modal').modal('show');
                            $('#item_price_old_view').val(toRp(price));
                            $('#item_cost_old_view').val(toRp(cost));
                            $('#item_cost_new_view').val(toRp(cost_new));
                            $('#item_price_old').val(price);
                            $('#item_cost_old').val(cost);
                            $('#item_cost_new').val(cost_new);
                        }
                    }
                }
            });

            var quantity = $("#quantity").val();
            var subtotal = quantity * cost_new;

            $("#subtotal_amount").val(subtotal);
            $("subtotal_amount_view").val(toRp(subtotal));
            $("#subtotal_amount_after_discount_view").val(toRp(subtotal));
            $("#subtotal_amount_after_discount").val(subtotal);
            $("#item_unit_cost_view").val(toRp(cost_new));
            $("#item_unit_cost").val(cost_new);
        });

        $("#discount_percentage").change(function(){
            var subtotal = parseInt($("#subtotal_amount").val());
            var discount_percentage = parseInt($("#discount_percentage").val());
            var discount_amount = (subtotal * discount_percentage) / 100;

            $('#discount_amount_view').val(toRp(discount_amount));
            $('#discount_amount').val(discount_amount);

            var subtotal_amount_after_discount = parseInt($("#subtotal_amount_after_discount").val());
            var total_amount = subtotal - discount_amount;

            $("#subtotal_amount_after_discount_view").val(toRp(total_amount));
            $("#subtotal_amount_after_discount").val(total_amount);
        });

        $("#discount_amount_view").change(function(){
            var subtotal = parseInt($("#subtotal_amount").val());
            var discount_amount = parseInt($("#discount_amount_view").val());
            var total_amount = subtotal - discount_amount;

            $('#subtotal_amount_after_discount_view').val(toRp(total_amount));
            $('#subtotal_amount_after_discount').val(total_amount);

            var discount_percentage = (discount_amount / subtotal) * 100;

            $('#discount_percentage').val(discount_percentage.toFixed(2));
            $('#discount_amount').val(discount_amount);
            $('#discount_amount_view').val(toRp(discount_amount));
        });

        $("#paid_amount_view").change(function(){
            if($("#paid_amount_view").val() == ''){
                var paid_amount = 0;
            } else{
                var paid_amount = parseInt($("#paid_amount_view").val());
            }
            var total_amount = parseInt($("#total_amount").val());
            var owing_amount = paid_amount - total_amount;
            $('#paid_amount_view').val(toRp(paid_amount));
            $('#paid_amount').val(paid_amount);
            $("#owing_amount").val(Math.abs(owing_amount));
            $("#owing_amount_view").val(toRp(Math.abs(owing_amount)));
        });
    });

    function proccess_change_cost(){
        var item_package_id         = document.getElementById("item_package_id").value;
        var item_cost_new           = document.getElementById("item_cost_new").value;
        var item_price_new          = document.getElementById("item_price_new").value;
        var margin_percentage       = document.getElementById("margin_percentage").value;

        $.ajax({
            type: "POST",
            url: "{{route('process-change-cost-purchase-invoice')}}",
            data: {
                'item_package_id'           : item_package_id,
                'item_cost_new'             : item_cost_new,
                'item_price_new'            : item_price_new,
                'margin_percentage'         : margin_percentage,
                '_token'                    : '{{csrf_token()}}'
            },
            success: function(msg){
                $('#modal').modal('hide');
                $('#alert').html("<div class= 'alert alert-info' role='alert'>"+msg+"</div>");
            }
        });
    }

    function processAddArrayPurchaseInvoice(){
        var item_package_id                         = document.getElementById("item_package_id").value;
        var item_unit_cost                          = document.getElementById("item_unit_cost").value;
        var quantity                                = document.getElementById("quantity").value;
        var discount_percentage                     = document.getElementById("discount_percentage").value;
        var discount_amount                         = document.getElementById("discount_amount").value;
        var subtotal_amount_after_discount          = document.getElementById("item_package_id").value;
        var subtotal_amount                         = document.getElementById("subtotal_amount").value;
        var item_expired_date                       = document.getElementById("item_expired_date").value;

        $.ajax({
            type: "POST",
            url: "{{route('add-array-purchase-invoice')}}",
            data: {
                'item_package_id'                   : item_package_id,
                'item_unit_cost'                    : item_unit_cost,
                'quantity'                          : quantity,
                'discount_percentage'               : discount_percentage,
                'discount_amount'                   : discount_amount,
                'subtotal_amount_after_discount'    : subtotal_amount_after_discount,
                'subtotal_amount'                   : subtotal_amount,
                'item_expired_date'                 : item_expired_date,
                '_token'                            : '{{csrf_token()}}',
            },
            success: function(msg){
                location.reload();
            }
        });
    }
    function reset_add(){
        $.ajax({
            type: "GET",
            url: "{{route('add-reset-purchase-invoice')}}",
            success: function(msg){
                location.reload();
            }
        });
    }

    $(document).ready(function(){
        $("#item_package_id").select2("val", "0");

        $("#item_packafe_id").change(function(){
            $('#subtotal_amount').val('');
                $('#subtotal_amount_view').val('');
                $('#discount_percentage').val('');
                $('#discount_amount').val('');
                $('#quantity').val('');
                $('#discount_amount_view').val('');
                $('#subtotal_amount_after_discount').val('');
                $('#subtotal_amount_after_discount_view').val('');
            if (this.value != '') {
                $.ajax({
                    url: "{{ url('select-item-cost') }}"+'/'+this.value,
                    type: "GET",
                    dataType: "html",
                    success:function(data)
                    {
                        $('#item_unit_cost').val(data);
                        $('#item_unit_cost_view').val(toRp(data));
                    }
                });
            } else {
                $('#item_unit_cost').val('');
                $('#item_unit_cost_view').val('');
                $('#subtotal_amount').val('');
                $('#subtotal_amount_view').val('');
                $('#discount_percentage').val('');
                $('#discount_amount').val('');
                $('#quantity').val('');
                $('#discount_amount_view').val('');
                $('#subtotal_amount_after_discount').val('');
                $('#subtotal_amount_after_discount_view').val('');
            }
		});
	});

    function final_total(name, value){
        var total_amount = parseInt($('#subtotal_amount_total').val());
        if (name == 'discount_percentage_total') {
            var discount_percentage_total = parseInt(value);
            var tax_ppn_percentage = parseInt($('#tax_ppn_percentage').val()) || 0;
            var shortover_amount = parseInt($('#shortover_amount').val()) || 0;
            var paid_amount = parseInt($("#paid_amount").val()) || 0;
            var discount_amount_total = Math.floor((total_amount * discount_percentage_total) / 100);
            var total_amount_after_diskon = total_amount - discount_amount_total;
            var tax_ppn_amount = Math.floor((total_amount_after_diskon * tax_ppn_percentage) / 100);
            var final_total_amount = total_amount_after_diskon + tax_ppn_amount + shortover_amount;
            var owing_amount = paid_amount - final_total_amount;

            $('#discount_amount_total').val(discount_amount_total);
            $('#discount_amount_total_view').val(toRp(discount_amount_total));
            $('#total_amount_view').val(toRp(final_total_amount));
            $('#total_amount').val(final_total_amount);
            $("#owing_amount").val(Math.abs(owing_amount));
            $("#owing_amount_view").val(toRp(Math.abs(owing_amount)));
            $('#tax_ppn_amount').val(tax_ppn_amount);
            $('#tax_ppn_amount_view').val(toRp(tax_ppn_amount));

        } else if (name == 'tax_ppn_percentage') {

            var tax_ppn_percentage = parseInt(value);
            var discount_amount_total = parseInt($('#discount_amount_total').val()) || 0;
            var shortover_amount = parseInt($('#shortover_amount').val()) || 0;
            var paid_amount = parseInt($("#paid_amount").val()) || 0;
            var total_amount_after_diskon = total_amount - discount_amount_total;
            var tax_ppn_amount = Math.floor((total_amount_after_diskon * tax_ppn_percentage) / 100);
            var final_total_amount = total_amount_after_diskon + tax_ppn_amount + shortover_amount;
            var owing_amount = paid_amount - final_total_amount;

            $('#tax_ppn_amount').val(tax_ppn_amount);
            $('#tax_ppn_amount_view').val(toRp(tax_ppn_amount));
            $('#total_amount_view').val(toRp(final_total_amount));
            $('#total_amount').val(final_total_amount);
            $("#owing_amount").val(Math.abs(owing_amount));
            $("#owing_amount_view").val(toRp(Math.abs(owing_amount)));

        } else if (name == 'shortover_amount_view') {

            var shortover_amount_view = parseInt(value);
            var tax_ppn_amount = parseInt($('#tax_ppn_amount').val()) || 0;
            var discount_amount_total = parseInt($('#discount_amount_total').val()) || 0;
            var paid_amount = parseInt($("#paid_amount").val()) || 0;
            var final_total_amount = (total_amount - discount_amount_total + tax_ppn_amount) + shortover_amount_view;
            var owing_amount = paid_amount - final_total_amount;

            $('#shortover_amount_view').val(toRp(shortover_amount_view));
            $('#shortover_amount').val(shortover_amount_view);
            $('#total_amount_view').val(toRp(final_total_amount));
            $('#total_amount').val(final_total_amount);
            $("#owing_amount").val(Math.abs(owing_amount));
            $("#owing_amount_view").val(toRp(Math.abs(owing_amount)));

        } else if (name = 'discount_amount_total_view') {

            var discount_amount_total = parseInt(value);
            var tax_ppn_percentage = parseInt($('#tax_ppn_percentage').val()) || 0;
            var shortover_amount = parseInt($('#shortover_amount').val()) || 0;
            var paid_amount = parseInt($("#paid_amount").val()) || 0;
            var discount_percentage_total = Math.floor((discount_amount_total / total_amount) * 100);
            var total_amount_after_diskon = total_amount - discount_amount_total;
            var tax_ppn_amount = Math.floor((total_amount_after_diskon * tax_ppn_percentage) / 100);
            var final_total_amount = total_amount_after_diskon + tax_ppn_amount + shortover_amount;
            var owing_amount = paid_amount - final_total_amount;

            $('#discount_percentage_total').val(discount_percentage_total);
            $('#discount_amount_total_view').val(toRp(discount_amount_total));
            $('#discount_amount_total').val(discount_amount_total);
            $('#total_amount_view').val(toRp(final_total_amount));
            $('#total_amount').val(final_total_amount);
            $("#owing_amount").val(Math.abs(owing_amount));
            $("#owing_amount_view").val(toRp(Math.abs(owing_amount)));
            $('#tax_ppn_amount').val(tax_ppn_amount);
            $('#tax_ppn_amount_view').val(toRp(tax_ppn_amount));

        }
    }

    $(document).ready(function(){
        var total_amount = parseInt($('#subtotal_amount_total').val());
        var tax_ppn_percentage = parseInt($('#tax_ppn_percentage').val());
        var discount_amount_total = parseInt($('#discount_amount_total').val()) || 0;
        var shortover_amount = parseInt($('#shortover_amount').val()) || 0;
        var total_amount_after_diskon = total_amount - discount_amount_total;
        var tax_ppn_amount = Math.floor((total_amount_after_diskon * tax_ppn_percentage) / 100);
        var final_total_amount = total_amount_after_diskon + tax_ppn_amount + shortover_amount;

        $('#tax_ppn_amount').val(tax_ppn_amount);
        $('#tax_ppn_amount_view').val(toRp(tax_ppn_amount));
        $('#total_amount_view').val(toRp(final_total_amount));
        $('#total_amount').val(final_total_amount);

        var paid_amount = parseInt($("#paid_amount_view").val()) || 0;
        var total_amount = parseInt($("#total_amount").val());
        var owing_amount = paid_amount - total_amount;

        $('#paid_amount_view').val(toRp(paid_amount));
        $('#paid_amount').val(paid_amount);
        $('#owing_amount').val(Math.abs(owing_amount));
        $('#owing_amount_view').val(toRp(Math.abs(owing_amount)));

        var purchase_payment_method = $('#purchase_payment_method').val();

        if(purchase_payment_method == 0){
            $('#due_date').addClass('d-none');
        } else{
            $('#due_date').removeClass('d-none');
        }

        $('#purchase_payment_method').change(function(){
            if(this.value == 0){
                $('#due_date').addClass('d-none');
            } else{
                $('#due_date').removeClass('d-none');
            }
        });

        var payment_method = {!! json_encode(session('purchase_payment')) !!};

        if (payment_method == 0){
            window.open("{{route('print-proof-acceptance-item')}}",'_blank');
            window.open("{{route('print-proof-expenditure-cash')}}",'_blank');
        } else if(payment_method ==1){
            window.open("{{route('print-proof-acceptance-item')}}",'_blank');
        }

        $('#item_unit_id_2').select2('val','0');
        $('#item_unit_id_3').select2('val','0');
        $('#item_unit_id_4').select2('val','0');

        $('#item_cost_1').change(function(){
            $.ajax({
                type: "POST",
                url: "{{route('count-margin-add-item')}}",
                data: {
                    'item_unit_cost'        : this.value,
                    'item_category_id'      :$('#item_category_id').val(),
                    '_token'                :'{{csrf_token()}}'
                },
                success: function(msg){
                    $('#item_price_1').val(msg);
                }
            });
        });

        $('#item_cost_2').change(function() {
            $.ajax({
                    type: "POST",
                    url : "{{route('count-margin-add-item')}}",
                    data : {
                        'item_unit_cost'    : this.value,
                        'item_category_id'  : $('#item_category_id').val(),
                        '_token'            : '{{csrf_token()}}'
                    },
                    success: function(msg){
                        $('#item_price_2').val(msg);
                }
            });
        });

        $('#item_cost_3').change(function() {
            $.ajax({
                    type: "POST",
                    url : "{{route('count-margin-add-item')}}",
                    data : {
                        'item_unit_cost'    : this.value,
                        'item_category_id'  : $('#item_category_id').val(),
                        '_token'            : '{{csrf_token()}}'
                    },
                    success: function(msg){
                        $('#item_price_3').val(msg);
                }
            });
        });

        $('#item_cost_4').change(function() {
            $.ajax({
                    type: "POST",
                    url : "{{route('count-margin-add-item')}}",
                    data : {
                        'item_unit_cost'    : this.value,
                        'item_category_id'  : $('#item_category_id').val(),
                        '_token'            : '{{csrf_token()}}'
                    },
                    success: function(msg){
                        $('#item_price_4').val(msg);
                }
            });
        });

        $('#purchase_invoice_due_day').change(function(){
            if (this.value != '') {
                var date_invoice = new Date($('#purchase_invoice_date').val());
                date_invoice.setDate(date_invoice.getDate() + parseInt(this.value));
                var date_str = date_invoice.toISOString();
                var day = date_str.substring(8, 10);
                var month = date_str.substring(5, 7);
                var year = date_str.substring(0, 4);
                var due_date = year + '-' + month + '-' + day;

                $('#purchase_invoice_due_date').val(due_date);

                setTimeout(() => {
                    function_elements_add('purchase_invoice_due_date', due_date);
                }, 100);
            } else {
                var date_invoice = new Date($('#purchase_invoice_date').val());
                date_invoice.setDate(date_invoice.getDate() + 0);
                var date_str = date_invoice.toISOString();
                var day = date_str.substring(8, 10);
                var month = date_str.substring(5, 7);
                var year = date_str.substring(0, 4);
                var due_date = year + '-' + month + '-' + day;

                $('#purchase_invoice_due_date').val(due_date);

                setTimeout(() => {
                    function_elements_add('purchase_invoice_due_date', due_date);
                }, 100);
            }
        });

        $('#purchase_invoice_due_date').change(function(){
            var due_date = new Date(this.value);
            var date_invoice = new Date($('#purchase_invoice_date').val());
            var difference = due_date.getTime() - date_invoice.getTime();
            var due_day_date = difference / (1000 * 3600 * 24);

            $('#purchase_invoice_due_day').val(due_day_date);

            setTimeout(() => {
                function_elements_add('purchase_invoice_due_day', due_day_date);
            }, 100);
        });

    });
    function check(){
        method = $('#purchase_payment_method').val()
        pay = parseInt($("#paid_amount").val())
        if(method ==0&&pay==0){
            alert("Kolom Dibayar Harus Diisi !");
            $('#save-transaction').prop('disabled',false);
            return 0;
        } else{
            $('#form-invoice').submit();
        }
    }
</script>
@stop
@section('content_header')

<nav arial-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{route('purchase_invoice')}}">Daftar Pembelian</a></li>
        <li class="breadcrumb-item" aria-current="page">Tambah Pembelian</li>
    </ol>
  </nav>

@stop

@section('content')
<h3>
    Form Tambah Pembelian
</h3>
<br/>
