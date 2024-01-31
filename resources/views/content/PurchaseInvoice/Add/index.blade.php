@inject('PurchaseInvoInvoice','App\Http\Controller','PurchaseInvoiceController')
@extends('adminlte::page')

@section('title','MOZAIC Minimarket')
@section('js')
<script>
    function function_elements_add(name, value){
        $.ajax({
            type: "POST",
            url:  "{{route('PI.add-elements')}}",
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

                if(Number.isIntegera(margin_percentage)){
                    $('#margin_percentage').val(margin_percentage);
                } else{
                    $('#margin_percentage').val(margin_percentage.toFixed(2));
                }
                $('#item_price_new_view').val(toRp(price_new));
                $('#item_price_new').val(price_new);
        });
    })
</script>
