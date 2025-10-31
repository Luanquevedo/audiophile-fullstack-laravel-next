import type { Metadata } from "next";
import { Manrope } from "next/font/google";
import "@/styles/global.scss";

const manrope = Manrope({
  variable: "--font-manrope",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Audiophile Store",
  description: "E-commerce fullstack de produtos de áudio desenvolvido com Laravel e Next.js",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body
        className={`${manrope.variable} antialiased`}
      >
        {children}
      </body>
    </html>
  );
}
