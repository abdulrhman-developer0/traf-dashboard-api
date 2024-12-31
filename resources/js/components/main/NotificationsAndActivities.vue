<script setup>
import { usePage  } from '@inertiajs/vue3'

import moment from 'moment'
import "moment/dist/locale/ar"
moment.locale('ar_SA')

const page = usePage()
const activities = computed(() => page.props.activities)
const notifications = computed(() => page.props.notifications)


const resolvActivityIcon = (act) => {
    if(act == 'new_client_registered') return 'tabler-user-plus'
    if(act == 'service_booked') return 'tabler-calendar-plus'
    if(act == 'service_canceled') return 'tabler-calendar-x'
    if(act == 'package_subscribed') return 'tabler-package-import'
    if(act == 'payment_made') return 'tabler-cash-banknote'
    if(act == 'refund_processed') return 'tabler-cash-banknote-off'

}

</script>

<template>
    <div class="notificationsSidebar pt-4 px-3">
        <div class="mb-6">
        <h3>الإشعارات</h3>
            <VList v-if="notifications.length">

            </VList>
            <VListItem v-else class="border px-1 mt-3 text-center">
                لا يوجد اشعارات جديدة
            </VListItem>
        </div>
        
        <div class="mb-6">
            <h3>الأنشطة</h3>
            <VList v-if="activities.length">
                <VListItem v-for="item in activities">
                    
                    <VListItemTitle>
                        {{ item.title }}
                    </VListItemTitle>
                    <VListItemTitle class="secondTitle">
                        {{ item.description }}
                    </VListItemTitle>
                    <VListItemSubtitle>
                        {{ moment(item.created_at).fromNow() }}
                    </VListItemSubtitle>

                    <template #append>
                        <VListItemAction start>
                            <VIcon :icon="resolvActivityIcon(item.action)" size="20"  />
                        </VListItemAction>
                    </template>
                </VListItem>

            </VList>

            <VListItem class="border px-1 mt-3 text-center" v-else>
                لا يوجد أنشطة جديدة
            </VListItem>
        </div>
    </div>

</template>


