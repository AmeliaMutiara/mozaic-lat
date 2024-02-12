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
    function changeCategory(id, el){
        loadingWidget();
        var merchant_id = $("#" + id).val();
        $('#merchant_id').val(merchant_id);
        $.ajax({
            type: "POST",
            url: "{{ route('pi.get-category')}}",
            dataType: "html",
            data: {
                'merchant_id' : merchant_id,
                '_token' : '{{csrf_token()}}',
            }
            success: function(return_data){
                function_elements_add(id, merchant_id);
                $('#' + el).html(return_data);
                changeItem($('#' + el).val());
                changeWarehouse(id);
            }
        });
    }
    function changeItem(category){
        loadingWidget();
        var id = $("#merchant_id").val();
        var no = $('.pkg-itm').length;
        $.ajax({
            type: "POST",
            url: "{{ route(pi.get-item) }}",
            dataType: "html",
            data: {
                'no': no,
                'merchant_id' : id,
                'item_category_id' : category,
                '_token' : '{{csrf_token() }}',
            },
            success: function(return_data){
                $('#item_id').val(1);
                $('#item_id').html(return_data);
                changeSatuan();
                function_elements_add('item_category_id', category);
            }
        });
    }
    function changeSatuan(){
        var item_id = $("#item_id").val();
        loadingWidget();
        $.ajax({
            type: "POST",
            url: "{{ route('pi.get-unit') }}",
            dataType: "html",
            data: {
                'item_id' : item_id,
                '_token' : '{{csrf_token() }}',
            },
            success: function(return_data){
                $('#item_unit').val(1);
                $('#item_unit').html(return_data);
                changeCost();
                function_elements_add('item_id',item_id);
            },
            complete: function(){
                loadingWidget(0);
                setTimeout(function(){
                    loadingWidget(0);
                }, 200);
            },
            error: function(data){
                console.log(data);
            }
        });
    }
    function changeCost(){
        loadingWidget();
        var item_unit = $("#item_unit").val();
        var item_id = $("item_id").val();
        $.ajax({
            type: "POST",
            url: "{{ route('get-item-cost')}}",
            dataType: "json",
            data: {
                'item_id': item_id,
                'item_unit': item_unit,
                '_token': '{{ csrf_token() }}';
            },
            success: function(return_data){
                
            }
        })
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
                dataType: 
            })
        })

    })
</script>
