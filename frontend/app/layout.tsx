import type { Metadata } from 'next'
import './globals.css'
import Header from '@/components/Header'
import Footer from '@/components/Footer'

export const metadata: Metadata = {
  title: 'Portal Kibica',
  description: 'By Filip Sankowski',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="pl">
      <body className="flex flex-col min-h-screen">
        <div>
          <Header />
        </div>
      
        {children}

        <div className="bottom-0">
          <Footer />
        </div>        
      </body>
    </html>
  )
}
