<?php
/**
 * Table Migration
 * @package  5.0
**/

use App\Classes\Hook;
use App\Classes\Schema;;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        if ( ! Schema::hasTable( 'nexopos_orders_coupons' ) ) {
            Schema::createIfMissing( 'nexopos_orders_coupons', function( Blueprint $table ) {
                $table->bigIncrements( 'id' );
                $table->integer( 'coupon_id' );
                $table->integer( 'order_id' );
                $table->integer( 'author' );
                $table->float( 'value' );
                $table->string( 'uuid' )->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        if ( Schema::hasTable( 'nexopos_orders_coupons' ) ) {
            Schema::dropIfExists( 'nexopos_orders_coupons' );
        }
    }
}

