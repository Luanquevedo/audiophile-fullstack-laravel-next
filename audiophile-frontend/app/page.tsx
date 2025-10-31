"use client";

import { useEffect, useState } from "react";

export default function HomePage() {
  const [msg, setMsg] = useState<string>("Carregando...");
  const [error, setError] = useState<string | null>(null);
//exemplo de conexao de api front x backend
  useEffect(() => {
    fetch(`${process.env.NEXT_PUBLIC_API_URL}`)
      .then(async (res) => {
        if (!res.ok) {
          throw new Error(`Erro HTTP ${res.status}`);
        }

        const data = await res.json(); // <- aqui já converte direto pra JSON
        console.log("✅ Resposta da API:", data);
        setMsg(data.message || "Sem resposta 😅");
      })
      .catch((err) => {
        console.error("❌ Erro ao conectar:", err);
        setError("Falha ao conectar com o backend");
      });
  }, []);

  return (
    <main style={{ padding: "2rem", fontFamily: "Manrope, sans-serif" }}>
      <h1>Audiophile 🎧</h1>
      <p>
        {error
          ? error
          : `Resposta do backend: ${msg}`}
      </p>
    </main>
  );
}
