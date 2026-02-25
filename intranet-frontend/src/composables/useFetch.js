import { ref } from 'vue'

export const useFetch = () => {
  const loading = ref(false)
  const error = ref(null)

  const run = async (fn) => {
    loading.value = true
    error.value = null
    try {
      return await fn()
    } catch (e) {
      error.value = e
      throw e
    } finally {
      loading.value = false
    }
  }

  return { loading, error, run }
}