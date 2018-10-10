<template>
    <div>
        <heading class="mb-6"><img src="https://developer.mailchimp.com/img/freddie_wink.svg"> MailChimp Tool</heading>
        <div>
            <div align="center">
                Subscribers: <span id="member_count">0</span> | UnSubscribers: <span id="unsubscribe_count">0</span>
            </div>
        </div>
        <br>
        <div style="color:green;">Add Subscriber</div>
        <br>
        <card class="flex-col items-center justify-center" style="min-height: 200px;">
            <div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label class="inline-block" for="email_address">
                           Email Address
                        </label>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <input v-model="email_address" type="email" id="email_address" class="w-full form-control form-input form-input-bordered" v-on:change="validateEmail('email_address')">
                    </div>
                </div>
            </div>
            <div class="px-4 py-2" align="center">
                <button class="ml-auto btn btn-default btn-primary mr-3" @click="addEmail">Add Subscriber</button>
            </div>
        </card>
        <br>
        <div style="color:red;">Remove Subscriber</div>
        <br>
        <card class="flex-col items-center justify-center" style="min-height: 200px">
            <div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label class="inline-block" for="email_address">
                            Email Address
                        </label>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <input v-model="email_address_delete" type="email" id="email_address_delete" class="w-full form-control form-input form-input-bordered" v-on:change="validateEmail('email_address_delete')">
                    </div>
                </div>
            </div>
            <div class="px-8 py-4" align="center">
                <button class="ml-auto btn btn-default btn-danger mr-3" @click="deleteEmail">Remove Subscriber</button>
            </div>
        </card>
        <br>
        <div>Send Email</div>
        <br>
        <card class="flex-col items-center justify-center" style="min-height: 300px">
            <div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label class="inline-block" for="subject">
                            Subject
                        </label>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <input v-model="subject" id="subject" type="text" class="w-full form-control form-input form-input-bordered">
                    </div>
                </div>
            </div>
            <div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label class="inline-block text-80 h-9 pt-2">Message</label>
                        <p class="text-sm leading-normal text-80 italic"></p>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <tinymce id="editor" v-model="body" :other_options="tinyOptions" @change="change" :content='content'></tinymce>

                    </div>
                </div>
            </div>
            <div class="px-8 py-4" align="center">
                <button class="ml-auto btn btn-default btn-primary mr-3" @click="sendEmail">Send Email</button>
            </div>
        </card>
        <br>
        <div>Latest 10 Subscribers</div>
        <br>
        <div align="center">
            <card class="flex-col items-center justify-center" style="min-height: 200px;">
                <br>
                <table class="table">
                    <tr>
                        <th>Email Address</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                    <tr v-for="subscriber in subscribers">
                        <td>{{subscriber.email_address}}</td>
                        <td>
                            {{formatDate(subscriber.timestamp_opt)}}
                        </td>
                        <td>{{subscriber.status}}</td>
                    </tr>
                </table>
                <br>
            </card>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import tinymce from 'vue-tinymce-editor'

export default {
    components: {
        tinymce
    },
    data() {
        return {
            email_address: "",
            subject: "",
            body: "",
            tinyOptions: {
                'height': 400
            },
            subscribers: []
        }
    },
    mounted() {
        Nova.request().get('/nova-vendor/mailchimp-tool/subscribers_count')
            .then(response => {
                var member_count = response.data.stats.member_count;
                var campaign_count = response.data.stats.campaign_count;
                var unsubscribe_count = response.data.stats.unsubscribe_count;
                document.getElementById('member_count').innerHTML = member_count;
                document.getElementById('unsubscribe_count').innerHTML = unsubscribe_count;
        });

        Nova.request().get('/nova-vendor/mailchimp-tool/subscribers')
            .then(response => {
               this.subscribers = response.data;
        });
    },
    methods: {
        addEmail() {
            if (!this.email_address.length) {
                this.$toasted.show('Please fill the email address field', {type: 'error'})
                return false
            }
            Nova.request()
                .post('/nova-vendor/mailchimp-tool/add', {
                    email_address: this.email_address
                })
                .then(response => {
                    if (response.data.status == 'subscribed'){
                        this.$toasted.show('Email added successfully!', { type: 'success' })
                    } else{
                        this.$toasted.show(response.data.title, { type: 'error' })
                    }
                })
        .catch(response => {
            this.$toasted.show('Email failed to add :(', {type: 'error'})
        });
        },
        deleteEmail() {
            if (!this.email_address_delete.length) {
                this.$toasted.show('Please fill the email address field', {type: 'error'})
                return false
            }
            Nova.request().post('/nova-vendor/mailchimp-tool/delete', {
                    email_address_delete: this.email_address_delete
            }).then(response => {
                if (response.data == true){
                    this.$toasted.show('Email deleted successfully!', { type: 'success' })
                } else{
                    if (response.data.title == 'Resource Not Found') {
                        this.$toasted.show('Email address not found', {type: 'error'})
                    }else{
                        this.$toasted.show(response.data.title, {type: 'error'})
                    }
                }
            })
            .catch(response => {
                    this.$toasted.show('Email failed to add :(', {type: 'error'})
            });
        },
        sendEmail() {
            if (!this.subject.length) {
                this.$toasted.show('Please fill the email subject field', {type: 'error'})
                return false
            }
            else if (!this.body.length) {
                this.$toasted.show('Please fill the email body content', {type: 'error'})
                return false
            }
            Nova.request()
                .post('/nova-vendor/mailchimp-tool/send', {
                    subject: this.subject,
                    body: this.body,
                })
                .then(response => {
                    if (response.data == true){
                        this.$toasted.show('Email sent successfully!', {type: 'success'})
                    } else{
                        this.$toasted.show('Email send failed!', {type: 'error'})
                    }
            })
            .catch(response => {
                    this.$toasted.show('Email failed to sent :(', {type: 'error'})
            });
        },
        validateEmail: function (id) {
            var email_field = document.getElementById(id);
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!re.test(String(email_field.value).toLowerCase())){
                email_field.value = '';
                email_field.style.border="1px solid red";
            } else{
                email_field.style.border="";
            }
        },
        formatDate: function (datetime) {
            var dateobj = new Date(datetime);
            return dateobj.getFullYear() +'-'+ String("00" + dateobj.getMonth()).slice(-2) +'-'+ dateobj.getDate();
        }
    },
}

</script>

<style>

</style>
