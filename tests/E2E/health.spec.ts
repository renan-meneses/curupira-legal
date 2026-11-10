import { test, expect } from '@playwright/test'

test('health endpoint is available', async ({ request }) => {
  const response = await request.get('/healthz')
  expect(response.ok()).toBeTruthy()
  expect((await response.json()).status).toBe('ok')
})

test('login entry renders the Curupira Legal shell', async ({ page }) => {
  await page.goto('/login')
  await expect(page).toHaveTitle(/Curupira Legal/)
  await expect(page.getByText('Curupira Legal')).toBeVisible()
})
