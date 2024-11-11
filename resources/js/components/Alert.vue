<script setup>
import { onMounted, onUpdated } from 'vue'
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

  const props = defineProps({
      status: {
          type: Object,
          default: {}
      },
  })

  function showSwal() {
      Swal.fire(
          props.status.action,
          props.status.text,
          props.status.type
      )
  }

  onMounted(() => {
      if (props.status) {
          showSwal()
      }
  })

  onUpdated(() => {
      if (props.status) {
          showSwal()
      }
  })
</script>
