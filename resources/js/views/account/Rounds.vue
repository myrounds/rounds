<template>
    <div>
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">
            <br>
            <h1>Manage Rounds</h1>

            <button class="mui-btn">Button</button>

            <p>
                fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag fjkdas fdsa fdsa
                fdsa fdsaf dsa fdsa fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag
                fjkdas fdsa fdsa fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag fj
                fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag fjkdas fdsa fdsa
                fdsa fdsaf dsa fdsa fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag
                fjkdas fdsa fdsa fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag fj
                fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag fjkdas fdsa fdsa
                fdsa fdsaf dsa fdsa fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag
                fjkdas fdsa fdsa fahjk fdshajk fhdsajk fdsaghkj ugjfas fadjksgfdhjksag fj
                fahjk fdshajk fhdsajk fdsaghkj.
            </p>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                loading: false,
                users: null,
                error: null,
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.error = this.users = null;
                this.loading = true;
                // axios
                //     .get('/api/members/find/1')
                //     .then(response => {
                //         console.log(response);
                //     });
            }
        }
    }
</script>