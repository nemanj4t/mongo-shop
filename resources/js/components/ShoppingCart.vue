<template>
    <div class="dropdown">
        <a href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-shopping-cart"></i>
            <span>Your Cart</span>
            <div v-if="shoppingCart.items > 0" class="qty">{{shoppingCart.items}}</div>
        </a>
        <div class="dropdown-menu p-2" style="width: 19em" aria-labelledby="dropdownMenuLink">
            <div class="cart-list p-2">
                <div v-for="product in shoppingCart.products" class="product-widget">
                    <div class="product-img">
                        <img :src="product.product.image" alt="">
                    </div>
                    <div class="product-body">
                        <h3 class="product-name"><a href="#">{{product.product.name}}</a></h3>
                        <h4 class="product-price">{{product.quantity}} x ${{product.product.price}}</h4>
                        <div class="mt-1">
                            <button class="btn btn-sm btn-dark" @click="addProductToCart(product)">+</button>
                            <button class="btn btn-sm btn-dark" @click="decrementProductQuantity(product)">-</button>
                        </div>
                    </div>
                    <button @click="removeProductFromCart(product)" class="delete"><i class="fas fa-window-close"></i></button>
                </div>
            </div>
            <div class="cart-summary">
                <small>{{shoppingCart.items}} Item(s) selected</small>
                <h5>SUBTOTAL: ${{shoppingCart.price}}</h5>
            </div>
            <div>
                <a class="btn btn-danger" style="width: 100%" href="/checkout">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'

    export default {
        methods: {
            ...mapMutations([
                'REMOVE_PRODUCT_FROM_CART',
                'ADD_PRODUCT_TO_CART',
                'DECREMENT_PRODUCT_QUANTITY_IN_CART'
            ]),

            addProductToCart(cartItem) {
                axios.post('/shoppingcart/add', {
                    'newProduct': cartItem
                }).then(response => {
                    this.ADD_PRODUCT_TO_CART(cartItem);
                });
            },

            removeProductFromCart(product) {
                axios.get('/shoppingcart/remove/' + product.product._id)
                    .then(response => {
                        this.REMOVE_PRODUCT_FROM_CART(product);
                    });
            },

            decrementProductQuantity(product) {
                if (product.quantity > 1) {
                    axios.get('/shoppingcart/decrement/' + product.product._id)
                        .then(response => {
                            this.DECREMENT_PRODUCT_QUANTITY_IN_CART(product);
                        });
                }
            }
        },

        computed: {
            shoppingCart() {
                return this.$store.getters.returnShoppingCart;
            }
        },

        mounted() {
            console.log('Component mounted.');
            axios.get('/shoppingcart/get')
                .then(response => {
                    if (response.data.hasOwnProperty('shoppingCart')) {
                        response.data.shoppingCart.cartItems.forEach(item => {
                            this.ADD_PRODUCT_TO_CART(item);
                        });
                    }
                });
        }
    }
</script>
