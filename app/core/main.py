from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.core.routers import compiler


app = FastAPI(
    title="Arduino Service",
    version="1.0.0",
    docs_url="/api/compiler/docs",
    redoc_url="/api/compiler/redoc",
    openapi_url="/api/compiler/openapi.json"
)


# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


@app.get("/api/compiler/health", tags=["Health"])
def health():
    return {"status": "ok"}

app.include_router(
    compiler.router, prefix="/api/compiler", tags=["Compilador"], responses={404: {"description": "Not Found"}})
