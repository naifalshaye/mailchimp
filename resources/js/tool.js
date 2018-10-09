Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'mailchimp-tool',
            path: '/mailchimp-tool',
            component: require('./components/Tool'),
        },
    ])

})
