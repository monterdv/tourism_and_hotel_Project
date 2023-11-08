import { createWebHistory } from "vue-router";

const dashboard = [
    {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("../dashboard/layouts/dashboard.vue"),
        meta: {
            name: "Admin",
            requiresAuth: true
        },
        children: [
            {
                path: "",
                component: () => import("../dashboard/pages/default.vue"), 
            },
            // user
            {
                path: "users",
                name: "users",
                component: () => import("../dashboard/pages/users/index.vue"),
                meta: {
                    name: "Admin - Manage Users",
                }
            },
            {
                path: "users/create",
                name: "users-create",
                component: () => import("../dashboard/pages/users/create.vue"),
                meta: {
                    name: "Admin - Create User",
                }
            }, {
                path: "users/:id/edit",
                name: "users-edit",
                component: () => import("../dashboard/pages/users/edit.vue"),
                meta: {
                    name: "Admin - Edit User",
                }
            },
            // places
            {
                path: "places",
                name: "places",
                component: () => import("../dashboard/pages/place/index.vue"),
                meta: {
                    name: "Admin - Manage Places",
                }
            },
            {
                path: "places/create",
                name: "places-create",
                component: () => import("../dashboard/pages/place/create.vue"),
                meta: {
                    name: "Admin - Create Place",
                }
            },
            {
                path: "places/:slug/edit",
                name: "places-edit",
                component: () => import("../dashboard/pages/place/edit.vue"),
                meta: {
                    name: "Admin - Edit Place",
                }
            },
            // Hotel
            {
                path: "hotel/:name?",
                name: "hotel",
                component: () => import("../dashboard/pages/hotel/index.vue"),
                meta: {
                    name: "Admin - Manage Hotels",
                }
            },
            {
                path: "hotel/create",
                name: "hotel-create",
                component: () => import("../dashboard/pages/hotel/create.vue"),
                meta: {
                    name: "Admin - Create Hotel",
                }
            },
            {
                path: "hotel/:slug/edit",
                name: "hotel-edit",
                component: () => import("../dashboard/pages/hotel/edit.vue"),
                meta: {
                    name: "Admin - Edit Hotel",
                }
            },
            // room
            {
                path: "hotel/:slug/room",
                name: "hotel-room",
                component: () => import("../dashboard/pages/hotel/Room/index.vue"),
                meta: {
                    name: "Admin - Manage Hotel Rooms",
                }
            },
            {
                path: "hotel/:slug/room/create",
                name: "hotel-room-create",
                component: () => import("../dashboard/pages/hotel/Room/create.vue"),
                meta: {
                    name: "Admin - Create Room",
                }
            },
            {
                path: "hotel/:slug/room/:slugRoom/edit",
                name: "hotel-room-edit",
                component: () => import("../dashboard/pages/hotel/Room/edit.vue"),
                meta: {
                    name: "Admin - Edit Room",
                }
            },
            // Hotel-widget
            {
                path: "hotel/widget/:name?",
                name: "hotel-widget",
                component: () => import("../dashboard/pages/hotel/widget/index.vue"),
                meta: {
                    name: "Admin - Manage Hotel Widgets",
                }
            },
            // tour
            {
                path: "tour",
                name: "tour",
                component: () => import("../dashboard/pages/Tour/index.vue"),
                meta: {
                    name: "Admin - Manage Tours",
                }
            },
            {
                path: "tour/create",
                name: "tour-create",
                component: () => import("../dashboard/pages/Tour/create.vue"),
                meta: {
                    name: "Admin - Create Tour",
                }
            },
            {
                path: "tour/:slug/edit",
                name: "tour-edit",
                component: () => import("../dashboard/pages/Tour/edit.vue"),
                meta: {
                    name: "Admin - Edit Tour",
                }
            },
            {
                path: "tour/:slug/time",
                name: "tour-time",
                component: () => import("../dashboard/pages/Tour/Time/index.vue"),
                meta: {
                    name: "Admin - Manage Tour Times",
                }
            },
            {
                path: "tour/:slug/time/create",
                name: "tour-time-create",
                component: () => import("../dashboard/pages/Tour/Time/create.vue"),
                meta: {
                    name: "Admin - Create Tour Time",
                }
            },
            {
                path: "tour/:slug/time/:id/edit",
                name: "tour-time-edit",
                component: () => import("../dashboard/pages/Tour/Time/edit.vue"),
                meta: {
                    name: "Admin - Edit Tour Time",
                }
            },
            //post
            {
                path: "posts",
                name: "posts",
                component: () => import("../dashboard/pages/post/index.vue"),
                meta: {
                    name: "Admin - Manage posts",
                }
            },
            {
                path: "posts/create",
                name: "posts-create",
                component: () => import("../dashboard/pages/post/create.vue"),
                meta: {
                    name: "Admin - Create post",
                }
            },
            {
                path: "posts/:slug/edit",
                name: "posts-edit",
                component: () => import("../dashboard/pages/post/edit.vue"),
                meta: {
                    name: "Admin - Edit post",
                }
            },
        ]
    },
    {
        path: "",
        name: "home",
        component: () => import("../home/layouts/home.vue"),
        meta: {
            name: "home",
        },
        children: [
            {
                path: "change-Password/:user/:token",
                name: "change-Password",
                component: () => import("../login/change-Password.vue"),
                meta: {
                    name: "change Password",
                },
            },
            {
                path: "/login",
                name: "login",
                component: () => import("../login/login.vue"),
                meta: {
                    name: "login",
                    guest: true,
                },
            },
            {
                path: "",
                name: "hotel-home",
                component: () => import("../home/pages/hotel/index.vue"), 
            },
            {
                path: "/hotel",
                name: "hotel-search",
                component: () => import("../home/pages/hotel/search/index.vue"), 
                props: route => ({
                    search: route.query.search || null,
                }),
            },
            {
                path: "/hotel/:slug",
                name: "hotel-detail",
                component: () => import("../home/pages/hotel/Detail/index.vue"), 
            },
            {
                path: "/profile",
                name: "profile",
                component: () => import("../home/pages/profile/index.vue"), 
                meta: {
                    name: "profile",
                    requiresAuth: true
                },
            },
            {
                path: "/tour",
                name: "tour-home",
                component: () => import("../home/pages/tour/index.vue"), 
            },
            {
                path: "/tour/search",
                name: "tour-search",
                component: () => import("../home/pages/tour/search.vue"), 
                props: route => ({
                    search: route.query.search || null,
                }),
            },
            {
                path: "/tour/:slug",
                name: "tour-detail",
                component: () => import("../home/pages/tour/Detail/index.vue"), 
            },
            {
                path: "/blog",
                name: "blog",
                component: () => import("../home/pages/blog/index.vue"), 
            },
            {
                path: "/blog/detail",
                name: "blog-detail",
                component: () => import("../home/pages/blog/blogDetail.vue"), 
            },

        ]
    },
    

]

export default dashboard;