<script>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'

const calendarOptions = {
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        weekends: false,
        events: [
          { title: 'Meeting', start: new Date() }
        ]
      }

</script>

<template>
    <VLayout style="z-index: 0;">
        qwdqwdqw
        <VCard flat>
            <FullCalendar
            :options='calendarOptions'
            />
        </VCard>
    
    </VLayout>
</template>

<style lang="scss">

</style>
